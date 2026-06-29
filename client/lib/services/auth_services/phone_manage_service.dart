import 'dart:async';

import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:prohandy_client/helper/constant_helper.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';

class PhoneManageService with ChangeNotifier {
  String? otp;
  String? phone;
  Timer? timer;
  bool loadingSendOTP = false;
  bool phoneVerifyLoading = false;

  bool canSend = false;
  Future tryOtpToPhone({
    String? phone,
  }) async {
    this.phone = phone ?? "";
    final data = {
      'phone': this.phone,
    };
    loadingSendOTP = true;
    notifyListeners();
    final responseData = await NetworkApiServices().postApi(
      data,
      AppUrls.sentOtpToPhoneUrl,
      LocalKeys.signUp,
    );

    if (responseData != null) {
      canSend = false;
      LocalKeys.otpSendToMailSuccessfully.showToast();
      timer = Timer(const Duration(seconds: 120), () {
        canSend = true;
        notifyListeners();
      });
      loadingSendOTP = false;
      notifyListeners();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  String? firebaseVerificationId;

  Future trySendingOtpToNewPhone({
    String? phone,
  }) async {
    if (phone != null) {
      this.phone = phone;
    }
    if (this.phone == null || this.phone!.isEmpty) {
      "Veuillez entrer un numéro de téléphone".showToast();
      return false;
    }
    loadingSendOTP = true;
    notifyListeners();
    try {
      final data = {
        'phone': this.phone,
      };
      final responseData = await NetworkApiServices().postApi(
        data,
        AppUrls.changePhoneUrl,
        LocalKeys.changePhone,
        headers: acceptJsonAuthHeader,
      );
      if (responseData != null) {
        canSend = false;
        timer = Timer(const Duration(seconds: 120), () {
          canSend = true;
          notifyListeners();
        });
        loadingSendOTP = false;
        notifyListeners();
        "Code envoyé !".showToast();
        return true;
      }
    } catch (e) {
      loadingSendOTP = false;
      notifyListeners();
      "Erreur d'envoi du code".showToast();
      return false;
    }
    loadingSendOTP = false;
    notifyListeners();
    return false;
  }

  Future tryPhoneVerify({
    required String phone,
    required String userId,
    required String token,
  }) async {
    this.phone = phone;
    final data = {
      'phone_verified': "1",
      "user_id": userId.toString(),
    };
    final headers = {
      'Accept': 'application/json',
      'Authorization': 'Bearer $token'
    };
    final responseData = await NetworkApiServices().postApi(
        data, AppUrls.verifyPhoneUrl, LocalKeys.signUp,
        headers: headers);
    if (responseData != null) {
      otp = responseData["otp"];
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }
  }

  Future tryPhoneChange({
    required String otp,
  }) async {
    phoneVerifyLoading = true;
    notifyListeners();
    try {
      final data = {
        'phone': phone,
        'otp_verify_status': '1',
        'otp': otp,
      };
      final responseData = await NetworkApiServices().postApi(
          data, AppUrls.changePhoneUrl, LocalKeys.changePhone,
          headers: acceptJsonAuthHeader);
      if (responseData != null) {
        LocalKeys.changedPhoneSuccessfully.showToast();
        phoneVerifyLoading = false;
        notifyListeners();
        return true;
      }
    } catch (e) {
      "Code incorrect ou expiré. Veuillez réessayer.".showToast();
    }

    phoneVerifyLoading = false;
    notifyListeners();
    return false;
  }

  Future tryPhoneSignIn({
    required String otp,
  }) async {
    final data = {
      'phone': phone,
      'otp_verify_status': '1',
      'otp': otp,
      "user_type": "1",
    };
    phoneVerifyLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .postApi(data, AppUrls.otpSignInUrl, LocalKeys.signIn);
    if (responseData != null) {
      LocalKeys.signedInSuccessfully.showToast();
      phoneVerifyLoading = false;
      final token = responseData["token"];
      setToken(token);
      notifyListeners();
      return true;
    } else if (responseData != null && responseData.containsKey("message")) {
      responseData["message"]?.toString().showToast();
    }

    phoneVerifyLoading = false;
    notifyListeners();
  }

  void setResetTimer() {
    loadingSendOTP = false;
    canSend = true;
  }
}
