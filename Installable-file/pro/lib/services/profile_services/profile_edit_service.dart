import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../view_models/service_area_view_model/service_area_view_model.dart';

class ProfileEditService {
  Future tryUpdatingBasicInfo() async {
    final pem = ProfileEditViewModel.instance;
    final data = {
      'first_name': pem.fNameController.text,
      'last_name': pem.lNameController.text,
      'date_of_birth': DateFormat("yyyy-MM-dd").format(pem.dob.value!),
      'about': pem.aboutController.text,
      'video_url': pem.videoUrlController.text,
    };
    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.profileInfoUpdateUrl));
    request.fields.addAll(data);
    request.headers.addAll(acceptJsonAuthHeader);
    if (pem.selectedGallery.value.isNotEmpty) {
      for (var i in pem.selectedGallery.value) {
        request.files
            .add(await http.MultipartFile.fromPath('store_images[]', i.path));
        debugPrint("image ${i.path} added".toString());
      }
    }
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.profileSetup,
    );
    if (responseData != null) {
      LocalKeys.profileInfoUpdated.showToast();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  tryUpdatingProfileImage() async {
    final pem = ProfileEditViewModel.instance;
    final data = {
      'update_type': 'after_login',
      'first_name': pem.fNameController.text,
      'last_name': pem.lNameController.text,
    };

    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.profileInfoUpdateUrl));
    request.files.add(await http.MultipartFile.fromPath(
        'file', pem.selectedImage.value!.path));
    request.headers.addAll(acceptJsonAuthHeader);
    request.fields.addAll(data);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.profileSetup,
    );

    if (responseData != null) {
      LocalKeys.profileInfoUpdated.showToast();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  tryUpdatingServiceAreas() async {
    final pem = ProfileEditViewModel.instance;
    final sam = ServiceAreaViewModel.instance;
    final data = {
      'state_id': pem.selectedState.value?.id.toString() ?? "",
      'city_id': pem.selectedCity.value?.id.toString() ?? "",
      'area_id': pem.selectedArea.value?.id.toString() ?? "",
      'address': pem.addressController.text,
      'post_code': pem.zipController.text,
      'latitude': "${pem.location.value?.lat ?? ""}",
      'longitude': "${pem.location.value?.lng ?? ""}",
    };
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.serviceAreaUpdateUrl));
    request.headers.addAll(acceptJsonAuthHeader);
    request.fields.addAll(data);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.profileSetup,
    );

    if (responseData != null) {
      LocalKeys.serviceAreaUpdated.showToast();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  tryUpdatingServiceTypes() async {
    final pem = ProfileEditViewModel.instance;
    final data = {
      'update_type': 'after_login',
      'first_name': pem.fNameController.text,
      'last_name': pem.lNameController.text,
      'service_categories':
          pem.categories.value.map((c) => c).toList().toString()
    };
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.profileInfoUpdateUrl));

    request.headers.addAll(acceptJsonAuthHeader);
    request.fields.addAll(data);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.profileSetup,
    );

    if (responseData != null) {
      LocalKeys.serviceTypesUpdated.showToast();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }
}
