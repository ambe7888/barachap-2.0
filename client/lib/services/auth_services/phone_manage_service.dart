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
  String? currentChannel;
  Timer? timer;
  bool loadingSendOTP = false;
  bool phoneVerifyLoading = false;

  bool canSend = false;
  Future tryOtpToPhone({
    String? phone,
  }) async {
    currentChannel = 'whatsapp';
    this.phone = phone ?? "";
    final data = {
      'phone': this.phone,
    };
    loadingSendOTP = true;
    notifyListeners();
    final responseData = await NetworkApiServices().postApi(
      data,
      AppUrls.sentOtpToPhoneUrl,
      LocalKeys.signIn,
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
    currentChannel = 'whatsapp';
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
      final tokenStr = getToken;
      final headers = {
        'Accept': 'application/json',
        'Authorization': 'Bearer $tokenStr'
      };

      final responseData = await NetworkApiServices().postApi(
        data,
        AppUrls.changePhoneUrl,
        LocalKeys.changePhone,
        headers: headers
      );

      if (responseData != null) {
        if (responseData['status'] == 'success' || responseData['message'] != null) {
          canSend = false;
          timer = Timer(const Duration(seconds: 120), () {
            canSend = true;
            notifyListeners();
          });
          loadingSendOTP = false;
          notifyListeners();
          "Code envoyé sur WhatsApp !".showToast();
          return true;
        } else {
           loadingSendOTP = false;
           notifyListeners();
           "Erreur d'envoi du code".showToast();
           return false;
        }
      } else {
        loadingSendOTP = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      loadingSendOTP = false;
      notifyListeners();
      "Erreur d'envoi du message WhatsApp".showToast();
      return false;
    }
  }

  Future<bool?> tryFirebaseOtp({required String phone, required BuildContext context}) async {
    currentChannel = 'sms';
    this.phone = phone;
    loadingSendOTP = true;
    notifyListeners();
    Completer<bool?> completer = Completer<bool?>();
    try {
      await FirebaseAuth.instance.verifyPhoneNumber(
        phoneNumber: phone,
        verificationCompleted: (PhoneAuthCredential credential) async {
          // Auto retrieval completed. We can sign in immediately if we wanted to.
        },
        verificationFailed: (FirebaseAuthException e) {
          loadingSendOTP = false;
          notifyListeners();
          e.message?.showToast();
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
      );
    } catch (e) {
      loadingSendOTP = false;
      notifyListeners();
      "Erreur d'envoi du SMS".showToast();
      if (!completer.isCompleted) completer.complete(false);
    }
    return completer.future;
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

    if (firebaseVerificationId != null) {
      try {
        PhoneAuthCredential credential = PhoneAuthProvider.credential(
          verificationId: firebaseVerificationId!, 
          smsCode: otp
        );
        await FirebaseAuth.instance.signInWithCredential(credential);
        // Fallback to update backend with dummy OTP or bypass
        otp = "000000"; 
      } catch (e) {
        "Code incorrect ou expiré. Veuillez réessayer.".showToast();
        phoneVerifyLoading = false;
        notifyListeners();
        return false;
      }
    }

    try {
      final data = {
        'phone': phone,
        if (firebaseVerificationId != null) 'firebase_verified': '1' else 'otp_verify_status': '1',
        if (firebaseVerificationId == null) 'otp': otp,
      };
      final tokenStr = getToken;
      final headers = {
        'Accept': 'application/json',
        'Authorization': 'Bearer $tokenStr'
      };

      final responseData = await NetworkApiServices().postApi(
          data, AppUrls.changePhoneUrl, LocalKeys.changePhone,
          headers: headers);
          
      if (responseData != null && responseData['message'] == 'Phone Number Changed Successfully.') {
        LocalKeys.changedPhoneSuccessfully.showToast();
        phoneVerifyLoading = false;
        notifyListeners();
        return true;
      } else if (responseData != null && responseData.containsKey("message")) {
        responseData["message"]?.toString().showToast();
      } else {
        "Code incorrect ou expiré. Veuillez réessayer.".showToast();
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
    phoneVerifyLoading = true;
    notifyListeners();

    if (firebaseVerificationId != null) {
      try {
        PhoneAuthCredential credential = PhoneAuthProvider.credential(
          verificationId: firebaseVerificationId!, 
          smsCode: otp
        );
        await FirebaseAuth.instance.signInWithCredential(credential);
        otp = "000000"; 
      } catch (e) {
        "Code incorrect ou expiré. Veuillez réessayer.".showToast();
        phoneVerifyLoading = false;
        notifyListeners();
        return false;
      }
    }

    final data = {
      'phone': phone,
      'otp_verify_status': '1',
      'otp': otp,
      "user_type": "1",
    };
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
