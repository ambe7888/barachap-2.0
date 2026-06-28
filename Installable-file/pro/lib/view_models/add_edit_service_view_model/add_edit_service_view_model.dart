import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../helper/svg_assets.dart';
import '../../models/category_model.dart';
import '../../services/service_services/add_edit_service_service.dart';
import '../../utils/components/alerts.dart';
import '../../views/add_edit_service_view/service_additional_info_view.dart';
import '../../views/add_edit_service_view/service_overview.dart';
import '../../views/service_price_view/service_price_view.dart';
import '../my_services_view_model/my_services_view_model.dart';

class AddEditServiceViewModel {
  final PageController pageController = PageController();

  final List<Widget> steps = [
    const ServiceOverview(),
    const ServiceAdditionalInfoView(),
    const ServicePriceView(),
  ];
  final TextEditingController titleController = TextEditingController();
  final TextEditingController unitController = TextEditingController();
  final TextEditingController priceController = TextEditingController();
  final TextEditingController videoUrlController = TextEditingController();
  final TextEditingController discountPriceController = TextEditingController();
  final TextEditingController descriptionController = TextEditingController();
  final ValueNotifier<Category?> categoryNotifier = ValueNotifier(null);
  final ValueNotifier<Category?> subcategoryNotifier = ValueNotifier(null);

  final ValueNotifier<List> selectedDays = ValueNotifier([]);
  final ValueNotifier<List<File>> gallery = ValueNotifier([]);
  final ValueNotifier<List> staffs = ValueNotifier([]);

  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  dynamic serviceId;
  final ValueNotifier<bool> addDiscount = ValueNotifier(false);
  final ValueNotifier<bool> enableAddons = ValueNotifier(true);
  final ValueNotifier<bool> selectStaffOption = ValueNotifier(true);
  final ValueNotifier<bool> anyStaffOption = ValueNotifier(true);
  final ValueNotifier<int> pageIndex = ValueNotifier(0);
  final ValueNotifier<List> faq = ValueNotifier([]);
  final ValueNotifier<List> excludes = ValueNotifier([]);
  final ValueNotifier<List> includes = ValueNotifier([]);
  final ValueNotifier<List> addons = ValueNotifier([]);
  final GlobalKey<FormState> includesFormKey = GlobalKey();
  final GlobalKey<FormState> overviewFormKey = GlobalKey();
  final GlobalKey<FormState> excludesFormKey = GlobalKey();
  final GlobalKey<FormState> faqFormKey = GlobalKey();

  addEditRemoveFAQ(int? index, {String? title, String? description}) {
    var tempFaq = faq.value;
    if (index == null) {
      tempFaq.add({
        "faq_service_title": title ?? "",
        "faq_service_description": description ?? "",
      });
    } else if (title != null) {
      tempFaq[index] = {
        "faq_service_title": title,
        "faq_service_description": description ?? "",
      };
    } else {
      tempFaq.removeAt(index);
    }
    faq.value = tempFaq;
  }

  addEditRemoveExcludes(int? index, {String? title, String? description}) {
    var tempExcludes = excludes.value;
    if (index == null) {
      tempExcludes.add({
        "exclude_service_title": title ?? "",
        "exclude_service_description": description ?? "",
      });
    } else if (title != null) {
      debugPrint(index.toString());
      debugPrint(tempExcludes.length.toString());
      tempExcludes[index] = {
        "exclude_service_title": title,
        "exclude_service_description": description ?? "",
      };
    } else {
      tempExcludes.removeAt(index);
    }
    excludes.value = tempExcludes;
  }

  addEditRemoveIncludes(int? index, {String? title, String? description}) {
    var tempIncludes = includes.value;
    if (index == null) {
      tempIncludes.add({
        "include_service_title": title ?? "",
        "include_service_description": description ?? "",
      });
    } else if (title != null) {
      tempIncludes[index] = {
        "include_service_title": title,
        "include_service_description": description ?? "",
      };
    } else {
      tempIncludes.removeAt(index);
    }
    includes.value = tempIncludes;
  }

  addEditRemoveAddons(int? index, {String? title, String? price, desc}) {
    var tempAddons = addons.value;
    if (index == null) {
      tempAddons.add({
        "addon_service_title": title,
        "addon_service_price": price,
        "addon_service_description": desc ?? "",
        "addon_service_image": ""
      });
    } else if (title != null) {
      tempAddons[index] = {
        "addon_service_title": title,
        "addon_service_price": price,
        "addon_service_description": desc ?? "",
        "addon_service_image": ""
      };
    } else {
      tempAddons.removeAt(index);
    }
    addons.value = tempAddons;
    debugPrint(addons.value.toString());
  }

  final ValueNotifier<List<File>> selectedGallery = ValueNotifier([]);
  final ValueNotifier<bool> imageLoading = ValueNotifier(false);
  final ValueNotifier<AdInfoType> selectedAdInfoType =
      ValueNotifier(AdInfoType.includes);

  AddEditServiceViewModel._init();
  static AddEditServiceViewModel? _instance;
  static AddEditServiceViewModel get instance {
    _instance ??= AddEditServiceViewModel._init();
    return _instance!;
  }

  static bool get dispose {
    _instance = null;
    return true;
  }

  bool get overviewValid {
    bool valid = true;
    if (!(overviewFormKey.currentState?.validate() ?? false)) {
      return false;
    }
    if (categoryNotifier.value == null) {
      LocalKeys.selectCategory.showToast();
      return false;
    }
    if (selectedGallery.value.isEmpty) {
      LocalKeys.addAtLeastOneImage.showToast();
      return false;
    }
    return valid;
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
    }));
    selectedGallery.value = set.toList();
  }

  void selectOrRemoveStaff(id) async {
    var tempStaffs = staffs.value;
    if (tempStaffs.contains(id.toString())) {
      tempStaffs.remove(id.toString());
    } else {
      tempStaffs.add(id.toString());
    }
    staffs.value = tempStaffs;
    staffs.notifyListeners();
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

  continueForward(BuildContext context) async {
    debugPrint(pageIndex.value.toString());
    switch (pageIndex.value) {
      case 0:
        final bool valid = overviewValid;
        if (!valid) return;
        break;
      case 1:
        break;
      case 2:
        bool? result = false;
        if (selectStaffOption.value &&
            !anyStaffOption.value &&
            staffs.value.isEmpty) {
          LocalKeys.chooseStaffToAllocate.showToast();
          return;
        }
        final msm = MyServicesViewModel.instance;
        if (serviceId == null) {
          await Alerts().confirmationAlert(
              context: context,
              title: LocalKeys.createService,
              buttonText: LocalKeys.create,
              buttonColor: primaryColor,
              onConfirm: () async {
                result = await Provider.of<AddEditServiceService>(context,
                        listen: false)
                    .tryCreatingService();
                context.pop;
              });
        } else {
          await Alerts().confirmationAlert(
              context: context,
              title: LocalKeys.editService,
              buttonText: LocalKeys.edit,
              buttonColor: primaryColor,
              onConfirm: () async {
                result = await Provider.of<AddEditServiceService>(context,
                        listen: false)
                    .tryEditingService(serviceId);
                context.pop;
              });
        }
        if (result == true) {
          Alerts()
              .showInfoDialogue(
                  context: context,
                  infoAsset: SvgAssets.addFilled.toSVGSized(100,
                      color: context.color.primarySuccessColor),
                  title: serviceId != null
                      ? LocalKeys.success
                      : LocalKeys.submitted,
                  description: serviceId != null
                      ? LocalKeys.serviceEditedSuccessfully
                      : LocalKeys.serviceSubmittedDesc)
              .then((_) {
            context.pop;
          });
          msm.refreshKey.currentState?.show();
        }

        return;

      default:
        debugPrint("default".toString());
    }
    debugPrint("default".toString());
    await pageController.nextPage(
        duration: 300.milliseconds, curve: Curves.easeIn);
    pageIndex.value = pageController.page?.toInt() ?? 0;
  }

  goBack(BuildContext context) async {
    await pageController.previousPage(
        duration: 300.milliseconds, curve: Curves.easeIn);
    pageIndex.value = pageController.page?.toInt() ?? 0;
  }

  void resetPage(int index) {
    pageController.jumpToPage(index);
    pageIndex.value = index;
  }

  void setDay(i) {
    var tempValue = selectedDays.value.toSet().toList();
    if (tempValue.contains(i)) {
      tempValue.remove(i);
      selectedDays.value = tempValue;
      return;
    }
    tempValue.add(i);
    selectedDays.value = tempValue;
  }

  initEdit(BuildContext context) {
    final serviceDetails =
        Provider.of<ServiceDetailsService>(context, listen: false)
            .serviceDetailsModel
            .allServices!;
    serviceId = serviceDetails.id;

    faq.value = serviceDetails.faqs?.map((fa) => fa.toFaq()).toList() ?? [];
    includes.value =
        serviceDetails.offers?.map((fa) => fa.toInclude()).toList() ?? [];
    excludes.value =
        serviceDetails.excludes?.map((fa) => fa.toExclude()).toList() ?? [];
    addons.value =
        serviceDetails.addons?.map((fa) => fa.toJson()).toList() ?? [];
    titleController.text = serviceDetails.title ?? "";
    priceController.text = serviceDetails.price.toString();
    videoUrlController.text = serviceDetails.videoUrl != null
        ? "https://www.youtube.com/watch?v=${serviceDetails.videoUrl}"
        : "";
    descriptionController.text = serviceDetails.description ?? "";
    unitController.text = serviceDetails.unit?.toString() ?? "";
    discountPriceController.text =
        serviceDetails.discountPrice?.toString() ?? "";
    categoryNotifier.value = serviceDetails.category;
    addDiscount.value = discountPriceController.text.tryToParse > 0;
    enableAddons.value = addons.value.isNotEmpty;
    if (serviceDetails.galleryImages?.isEmpty ?? true) {
      fetchAndSetGalleryImages([serviceDetails.image ?? ""]);
    } else {
      fetchAndSetGalleryImages(serviceDetails.galleryImages ?? []);
    }
    if ((serviceDetails.provider?.staffs ?? []).isEmpty) {
      selectStaffOption.value = false;
    } else {
      staffs.value = serviceDetails.provider!.staffs!
          .map((s) => s.id?.toString() ?? "")
          .toList();
    }
    if (!serviceDetails.allocatedStaffOnly) {
      anyStaffOption.value = false;
    }
  }

  void fetchAndSetGalleryImages(List<String> images) async {
    List<File> downloadedFiles = [];
    imageLoading.value = true;
    for (String url in images) {
      try {
        final response = await http.get(Uri.parse(url));
        if (response.statusCode == 200) {
          final directory = await getTemporaryDirectory();
          final filePath = '${directory.path}/${url.split('/').last}';
          final file = File(filePath);
          await file.writeAsBytes(response.bodyBytes);
          downloadedFiles.add(file);
        }
      } catch (e) {}
    }
    imageLoading.value = false;
    selectedGallery.value = downloadedFiles;
  }
}

enum AdInfoType { includes, excludes, faq }
