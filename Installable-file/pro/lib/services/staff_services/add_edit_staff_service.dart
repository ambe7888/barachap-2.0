import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/view_models/staff_view_model/staff_view_model.dart';
import 'package:provider/provider.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';

class AddEditStaffService {
  tryAddingStaff(BuildContext context) async {
    var url = AppUrls.addStaffUrl;
    final sm = StaffViewModel.instance;
    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com") &&
        Provider.of<ProfileInfoService>(context, listen: false)
                .profileInfoModel
                .userDetails
                ?.id
                ?.toString() ==
            "2") {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    var request = http.MultipartRequest('POST', Uri.parse(url));
    request.fields.addAll({
      'first_name': sm.firstNameController.text,
      'last_name': sm.lastNameController.text,
      'phone': sm.phoneController.text,
      'email': sm.emailController.text,
      'about': "",
    });
    if (sm.selectedImage.value != null) {
      request.files.add(await http.MultipartFile.fromPath(
        'image',
        sm.selectedImage.value!.path,
      ));
    }
    request.headers.addAll(acceptJsonAuthHeader);

    final responseData =
        await NetworkApiServices().postWithFileApi(request, LocalKeys.addStaff);

    if (responseData != null) {
      LocalKeys.staffAddedSuccessfully.showToast();
      return true;
    }
  }

  tryEditingStaff(BuildContext context) async {
    final sm = StaffViewModel.instance;
    var url = "${AppUrls.editStaffUrl}/${sm.staffId}";
    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com") &&
        Provider.of<ProfileInfoService>(context, listen: false)
                .profileInfoModel
                .userDetails
                ?.id
                ?.toString() ==
            "2") {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    var request = http.MultipartRequest('POST', Uri.parse(url));
    request.fields.addAll({
      "id": sm.staffId.toString(),
      'first_name': sm.firstNameController.text,
      'last_name': sm.lastNameController.text,
      'phone': sm.phoneController.text,
      'email': sm.emailController.text,
      'about': "",
    });
    if (sm.selectedImage.value != null) {
      request.files.add(await http.MultipartFile.fromPath(
        'image',
        sm.selectedImage.value!.path,
      ));
    }
    request.headers.addAll(acceptJsonAuthHeader);

    final responseData =
        await NetworkApiServices().postWithFileApi(request, LocalKeys.addStaff);

    if (responseData != null) {
      LocalKeys.staffEditedSuccessfully.showToast();
      return true;
    }
  }

  tryRemovingStaff(BuildContext context, id) async {
    var url = "${AppUrls.removeStaffUrl}$id";
    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com") &&
        Provider.of<ProfileInfoService>(context, listen: false)
                .profileInfoModel
                .userDetails
                ?.id
                ?.toString() ==
            "2") {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }

    final responseData = await NetworkApiServices()
        .postApi({}, url, LocalKeys.removeStaff, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.staffRemovedSuccessfully.showToast();
      return true;
    }
  }
}
