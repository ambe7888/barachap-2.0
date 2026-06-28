import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:prohand/view_models/sign_up_view_model/sign_up_view_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';

class SignUpService with ChangeNotifier {
  bool emailVerified = true;
  bool verifyEnabled = true;

  var emailToken = "";
  var token = "";
  var email = "";
  var userId = "";
  Future tryEmailSignUp(
      {required String emailUsername, required String password}) async {
    final data = {
      'email': emailUsername,
      'password': password,
      'type': '0',
      'terms_conditions': '1',
    };
    final responseData = await NetworkApiServices().postApi(
      data,
      AppUrls.emailSignUpUrl,
      LocalKeys.signUp,
    );

    if (responseData != null && responseData.containsKey("token")) {
      LocalKeys.youHaveSignedUpSuccessfully.showToast();
      token = responseData["token"] ?? "";
      emailVerified =
          responseData["user"]["is_email_verified"].toString().parseToBool;
      verifyEnabled = responseData["verify_enabled"].toString().parseToBool;
      emailToken =
          (responseData["user"]?["email_verify_token"])?.toString() ?? "";
      email = (responseData["user"]?["email"]) ?? "";
      userId = (responseData["user"]?["id"])?.toString() ?? "";
      return emailVerified || !verifyEnabled;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  Future tryToSetProfileInfos() async {
    final sum = SignUpViewModel.instance;
    final data = {
      'update_type': 'after_login',
      'first_name': sum.fNameController.text,
      'last_name': sum.lNameController.text,
      'date_of_birth': DateFormat("yyyy-MM-dd").format(sum.dob.value!),
    };
    var headers = {
      'Accept': 'application/json',
      'Authorization': 'Bearer $token'
    };
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.profileInfoUpdateUrl));
    request.fields.addAll(data);
    request.headers.addAll(headers);
    if (sum.profileImage.value != null) {
      request.files.add(await http.MultipartFile.fromPath(
          'file', sum.profileImage.value!.path));
    }
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.profileSetup,
    );

    if (responseData != null) {
      LocalKeys.profileSetupComplete.showToast();
      setToken(token);
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  void sToken(String token) {
    this.token = token;
    notifyListeners();
  }
}
