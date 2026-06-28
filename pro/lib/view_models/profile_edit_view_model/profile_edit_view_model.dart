import 'dart:io';

import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/profile_models/profile_info_model.dart';
import 'package:prohand/services/google_location_search_service.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:provider/provider.dart';

import '../../app_static_values.dart';
import '../../helper/local_keys.g.dart';
import '../../models/address_models/area_model.dart';
import '../../models/address_models/city_model.dart';
import '../../models/address_models/states_model.dart';
import '../../models/google_places_model.dart';
import '../../services/profile_services/profile_edit_service.dart';

class ProfileEditViewModel {
  final ValueNotifier<File?> selectedImage = ValueNotifier(null);
  final ValueNotifier<List> categories = ValueNotifier([]);

  final TextEditingController fNameController = TextEditingController();
  final TextEditingController lNameController = TextEditingController();
  final TextEditingController zipController = TextEditingController();
  final TextEditingController addressController = TextEditingController();
  final TextEditingController aboutController = TextEditingController();
  final TextEditingController videoUrlController = TextEditingController();

  final ValueNotifier<DateTime?> dob = ValueNotifier(null);
  final ValueNotifier<States?> selectedState = ValueNotifier(null);
  final ValueNotifier<City?> selectedCity = ValueNotifier(null);
  final ValueNotifier<Area?> selectedArea = ValueNotifier(null);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  final GlobalKey<FormState> basicFormKey = GlobalKey();
  final GlobalKey<FormState> idFormKey = GlobalKey();
  ValueNotifier<Prediction?> location = ValueNotifier(null);
  GoogleMapController? mapController;
  final ValueNotifier<List<File>> selectedGallery = ValueNotifier([]);
  final ValueNotifier<bool> imageLoading = ValueNotifier(false);

  addRemoveCategory(id) {
    final tmpList = categories.value.toSet().toList();
    if (tmpList.contains(id)) {
      tmpList.remove(id);
    } else {
      tmpList.add(id);
    }
    categories.value = tmpList;
  }

  void selectGalleryImage() async {
    final files = await ImagePicker().pickMultiImage().onError(
      (error, stackTrace) {
        error.toString().showToast();
        return [];
      },
    );
    Set<File> set = Set.from(selectedGallery
        .value); // Create a set from the first list to avoid duplicates
    set.addAll(files.map((f) {
          return File(f.path);
        }) ??
        []);
    selectedGallery.value = set.toList();
  }

  void removeFromGallery(File image) async {
    Set<File> set = Set.from(selectedGallery
        .value); // Create a set from the first list to avoid duplicates
    set.remove(image);

    selectedGallery.value = set.toList();
    try {
      if (await image.exists()) {
        await image.delete();
      }
    } catch (e) {
      debugPrint('Error deleting file from cache: $e');
    }
  }

  ProfileEditViewModel._init();
  static ProfileEditViewModel? _instance;
  static ProfileEditViewModel get instance {
    _instance ??= ProfileEditViewModel._init();
    return _instance!;
  }

  ProfileEditViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void selectProfileImage() async {
    try {
      final file =
          await ImagePicker().pickImage(source: ImageSource.gallery).onError(
        (error, stackTrace) {
          error.toString().showToast();
          return null;
        },
      );
      if (file?.path == null) {
        return;
      }
      final File imageFile = File(file!.path);
      num maxSize = 1024 * 1024 * (profileImageMaxSize ?? 1); // 1MB
      final num fileSize = await imageFile.length();

      if (fileSize > maxSize) {
        LocalKeys.fileSizeExceeded.showToast();
        return;
      }
      selectedImage.value = File(file.path);
      LocalKeys.fileSelected.showToast();
    } catch (error) {
      LocalKeys.fileSelectFailed.showToast();
    }
  }

  initProfile(UserDetails user) {
    fNameController.text = user.firstName ?? "";
    lNameController.text = user.lastName ?? "";
    aboutController.text = user.about ?? "";
    videoUrlController.text = user.videoUrl != null
        ? "https://www.youtube.com/watch?v=${user.videoUrl}"
        : "";
    dob.value = user.dateOfBirth;
    selectedArea.value =
        Area(id: user.serviceArea?.areaId, area: user.serviceArea?.areaName);
    selectedState.value = States(
        id: user.serviceArea?.stateId, state: user.serviceArea?.stateName);
    selectedCity.value =
        City(id: user.serviceArea?.cityId, city: user.serviceArea?.cityName);
    zipController.text = user.serviceArea?.postCode ?? "";
    addressController.text = user.serviceArea?.address ?? "";
    categories.value =
        user.serviceTypes?.map((c) => c.id.toString()).toList() ?? [];
    if (user.serviceArea?.latitude != null) {
      location.value = Prediction(
          lat: user.serviceArea!.latitude, lng: user.serviceArea!.longitude);
    }
  }

  initStoreImages(UserDetails user) async {
    List<File> downloadedFiles = [];
    imageLoading.value = true;
    debugPrint("images adding ${user.storeImages}".toString());
    for (var url in (user.storeImages ?? [])) {
      if (url == null) break;
      try {
        final response = await http.get(Uri.parse(url));
        if (response.statusCode == 200) {
          final directory = await getTemporaryDirectory();
          final filePath = '${directory.path}/${url.split('/').last}';
          final file = File(filePath);
          await file.writeAsBytes(response.bodyBytes);
          downloadedFiles.add(file);
          debugPrint("image added $url".toString());
        }
      } catch (e) {
        debugPrint(e.toString());
      }
    }
    imageLoading.value = false;
    selectedGallery.value = downloadedFiles;
  }

  initCat(List? cats) {}

  void updateBasicInfo(BuildContext context) async {
    isLoading.value = true;
    await ProfileEditService().tryUpdatingBasicInfo().then((v) {
      if (v != true) return;
      Provider.of<ProfileInfoService>(context, listen: false)
          .fetchProfileInfo();
    });
    isLoading.value = false;
  }

  void updateProfileImage(BuildContext context) async {
    isLoading.value = true;
    await ProfileEditService().tryUpdatingProfileImage().then((v) {
      if (v != true) return;
      Provider.of<ProfileInfoService>(context, listen: false)
          .fetchProfileInfo();
    });
    isLoading.value = false;
  }

  void updateServiceArea(BuildContext context) async {
    isLoading.value = true;
    location.value =
        Provider.of<GoogleLocationSearch>(context, listen: false).geoLoc;
    await ProfileEditService().tryUpdatingServiceAreas().then((v) {});
    isLoading.value = false;
  }

  void updateServiceType(BuildContext context) async {
    isLoading.value = true;
    await ProfileEditService().tryUpdatingServiceTypes().then((v) {});
    isLoading.value = false;
  }
}
