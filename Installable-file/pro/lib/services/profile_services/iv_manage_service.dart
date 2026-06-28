import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/profile_models/iv_info_model.dart';
import '../../view_models/identity_verify_view_model/identity_verify_view_model.dart';

class IvManageService with ChangeNotifier {
  IdentityVerificationInfoModel? _ivInfoModel;
  IdentityVerificationInfoModel get ivInfoModel =>
      _ivInfoModel ?? IdentityVerificationInfoModel();

  fetchIVInfo() async {
    var url = AppUrls.ivInfoUrl;

    final responseData = await NetworkApiServices().getApi(
        url, LocalKeys.identityVerification,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _ivInfoModel = IdentityVerificationInfoModel.fromJson(responseData);
      sPref?.setString("iv", jsonEncode(_ivInfoModel?.toJson() ?? {}));
      notifyListeners();
      return true;
    }
  }

  trySubmitForIV() async {
    final ivm = IVViewModel.instance;
    final data = {
      'identification_type': ivm.selectedIVType.value,
      'identification_number': ivm.idController.text,
      'country': ivm.countryController.text,
      'state': ivm.stateController.text,
      'city': ivm.cityController.text,
      'zip_code': ivm.zipController.text,
      'address': ivm.addressController.text,
    };
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.idVerifySubmitUrl));

    request.headers.addAll(acceptJsonAuthHeader);
    request.files.add(await http.MultipartFile.fromPath(
        'front_document', ivm.frontImage.value!.path));
    request.files.add(await http.MultipartFile.fromPath(
        'back_document', ivm.backImage.value!.path));
    request.fields.addAll(data);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.submitId,
    );

    if (responseData != null) {
      LocalKeys.idVerifySubmittedSuccessfully.showToast();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }
}
