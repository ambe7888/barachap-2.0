import 'package:flutter/material.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/view_models/change_password_view_model/change_password_view_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/local_keys.g.dart';

class ChangePasswordService {
  tryChangingPassword() async {
    final cpm = ChangePasswordViewModel.instance;

    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    final data = {
      'current_password': cpm.currentPassController.text,
      'new_password': cpm.newPassController.text
    };
    debugPrint(acceptJsonAuthHeader.toString());
    final responseData = await NetworkApiServices().postApi(
      data,
      AppUrls.changePasswordUrl,
      LocalKeys.changePassword,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      LocalKeys.passwordChangedSuccessfully.showToast();
      return true;
    }
  }
}
