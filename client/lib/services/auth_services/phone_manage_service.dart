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
      final completer = Completer<bool>();
      await FirebaseAuth.instance.verifyPhoneNumber(
        phoneNumber: this.phone!,
        verificationCompleted: (PhoneAuthCredential credential) async {
          await FirebaseAuth.instance.signInWithCredential(credential);
          loadingSendOTP = false;
          notifyListeners();
          if (!completer.isCompleted) completer.complete(true);
        },
        verificationFailed: (FirebaseAuthException e) {
          loadingSendOTP = false;
          notifyListeners();
          "Erreur de vérification: ${e.message}".showToast();
          if (!completer.isCompleted) completer.complete(false);
        },
        codeSent: (String verificationId, int? resendToken) {
          firebaseVerificationId = verificationId;
          canSend = false;
          timer = Timer(const Duration(seconds: 120), () {
            canSend = true;
            notifyListeners();
          });
          loadingSendOTP = false;
          notifyListeners();
          "Code envoyé par SMS !".showToast();
          if (!completer.isCompleted) completer.complete(true);
        },
        codeAutoRetrievalTimeout: (String verificationId) {
          firebaseVerificationId = verificationId;
        },
        timeout: const Duration(seconds: 120),
      );
      return completer.future;
    } catch (e) {
      loadingSendOTP = false;
      notifyListeners();
      "Erreur d'envoi du SMS".showToast();
      return false;
    }
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
    if (firebaseVerificationId == null) {
      "Une erreur s'est produite. Veuillez renvoyer le code.".showToast();
      return false;
    }
    phoneVerifyLoading = true;
    notifyListeners();
    try {
      final credential = PhoneAuthProvider.credential(
        verificationId: firebaseVerificationId!,
        smsCode: otp,
      );
      await FirebaseAuth.instance.signInWithCredential(credential);

      final data = {
        'phone': phone,
        'firebase_verified': '1',
      };
      final responseData = await NetworkApiServices().postApi(
          data, AppUrls.changePhoneUrl, LocalKeys.changePhone,
          headers: acceptJsonAuthHeader);
      if (responseData != null) {
        LocalKeys.changedPhoneSuccessfully.showToast();
        phoneVerifyLoading = false;
        notifyListeners();
        return true;
      } else if (responseData != null && responseData.containsKey("message")) {
        responseData["message"]?.toString().showToast();
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
