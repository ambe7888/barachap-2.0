import 'dart:io';

import 'package:flutter/material.dart';
import 'package:firebase_auth/firebase_auth.dart';
import 'package:image_picker/image_picker.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/services/auth_services/sign_up_service.dart';
import 'package:provider/provider.dart';

import '../../data/network/network_api_services.dart';

import '../../app_static_values.dart';
import '../../helper/app_urls.dart';
import '../../helper/local_keys.g.dart';
import '../../helper/phone_field.dart';
import '../../services/auth_services/email_otp_service.dart';
import '../../services/profile_services/profile_info_service.dart';
import '../../utils/components/alerts.dart';
import '../../views/reset_password/enter_otp_view.dart';
import '../../views/sign_up_view/sign_up_name_date.dart';
import '../landding_view_model/landding_view_model.dart';
import '../sign_in_view_model/sign_in_view_model.dart';

class SignUpViewModel {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final TextEditingController nameController = TextEditingController();
  final TextEditingController fNameController = TextEditingController();
  final TextEditingController lNameController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController otpController = TextEditingController();
  final ValueNotifier<Phone?> phone = ValueNotifier(null);
  String? firebaseVerificationId;
  final ValueNotifier<bool> isPhoneVerified = ValueNotifier(false);
  final ValueNotifier<bool> otpSent = ValueNotifier(false);
  final ValueNotifier<bool> otpLoading = ValueNotifier(false);
  bool signUpSuccess = false;

  final ValueNotifier<bool> passObs = ValueNotifier(true);
  final ValueNotifier<bool> passConfirmObs = ValueNotifier(true);
  final ValueNotifier<bool> rememberPass = ValueNotifier(true);
  final ValueNotifier<bool> loading = ValueNotifier(false);
  final ValueNotifier<bool> profileSetupLoading = ValueNotifier(false);
  final ValueNotifier<File?> profileImage = ValueNotifier(null);
  final ValueNotifier<DateTime?> dob = ValueNotifier(null);

  final GlobalKey<FormState> formKey = GlobalKey();
  final GlobalKey<FormState> piFormKey = GlobalKey();
  final ValueNotifier<List<dynamic>> categories = ValueNotifier([]);

  SignUpViewModel._init();
  static SignUpViewModel? _instance;
  static SignUpViewModel get instance {
    _instance ??= SignUpViewModel._init();
    return _instance!;
  }

  static bool get dispose {
    _instance = null;
    return true;
  }

  tryEmailSignUp(BuildContext context) async {
    final isValid = formKey.currentState?.validate();

    if (isValid == false) {
      return;
    }

    final sim = SignInViewModel.instance;
    loading.value = true;
    final siProvider = Provider.of<SignUpService>(context, listen: false);
    final sResult = await siProvider.tryEmailSignUp(
      emailUsername: emailController.text,
      password: passwordController.text,
    );
    if (sResult == true) {
      signUpSuccess = true;
    } else {
      final su = Provider.of<SignUpService>(context, listen: false);
      final result = await Provider.of<EmailManageService>(
        context,
        listen: false,
      ).tryOtpToEmail(emailUsername: emailController.text);
      if (result == true) {
        final otpResult = await Navigator.push(
          context,
          MaterialPageRoute(
            builder:
                (context) => EnterOtpView(
                  Provider.of<EmailManageService>(context, listen: false).otp,
                  email: su.email,
                  fromRegister: true,
                ),
          ),
        );
        if (otpResult != true) {
          debugPrint(otpResult.toString());
          await Alerts().showInfoDialogue(
            context: context,
            title: LocalKeys.verificationFailed,
            description: LocalKeys.accountCreatedButVerificationFailed,
          );
          context.pop;
          signUpSuccess = false;
        } else {
          final verifyResult = await Provider.of<EmailManageService>(
            context,
            listen: false,
          ).tryEmailVerify(
            emailUsername: emailController.text,
            userId: su.userId,
            token: su.token,
          );
          if (verifyResult == true) {
            signUpSuccess = true;
          }
        }
      }
    }

    debugPrint("Sign up success is $signUpSuccess".toString());
    if (!signUpSuccess) {
      loading.value = false;
      return;
    }
    await Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const SignUpNameDate()),
    );
    if (rememberPass.value == true) {
      sim.setUserInfo(
        email: emailController.text,
        pass: passwordController.text,
      );
    } else {
      sim.setUserInfo();
    }
    loading.value = false;
  }

  void selectProfileImage() async {
    try {
      final file = await ImagePicker().pickImage(source: ImageSource.gallery);
      if (file?.path == null) {
        return;
      }
      final File imageFile = File(file!.path);
      num maxSize = 1024 * 1024 * (profileImageMaxSize); // 1MB
      final num fileSize = await imageFile.length();

      if (fileSize > maxSize) {
        LocalKeys.fileSizeExceeded.showToast();
        return;
      }
      profileImage.value = File(file.path);
      LocalKeys.fileSelected.showToast();
    } catch (error) {
      LocalKeys.fileSelectFailed.showToast();
    }
  }

  void tryToSetProfileInfo(BuildContext context) async {
    profileSetupLoading.value = true;
    final result =
        await Provider.of<SignUpService>(
          context,
          listen: false,
        ).tryToSetProfileInfos();
    if (result == true) {
      Provider.of<ProfileInfoService>(
        context,
        listen: false,
      ).fetchProfileInfo().then((v) {
        if (v != true) return;
        try {
          SignInViewModel.instance.setUserInfo(
            email:
                emailController.text.isNotEmpty ? emailController.text : null,
            pass:
                passwordController.text.isNotEmpty
                    ? passwordController.text
                    : null,
          );
        } catch (e) {}
        LandingViewModel.instance.navigateToLanding(context);
      });
    }
    profileSetupLoading.value = false;
  }

  Future<void> sendOtpToPhone(BuildContext context) async {
    final localPhone = phoneController.text.trim();
    if (localPhone.isEmpty) {
      "Veuillez entrer un numéro de téléphone".showToast();
      return;
    }
    final dialCode = phone.value?.dialCode ?? '225';
    final fullPhone = "+$dialCode$localPhone";

    otpLoading.value = true;
    try {
      final data = {
        'phone': fullPhone,
      };
      final tokenStr = Provider.of<SignUpService>(context, listen: false).token;
      final headers = {
        'Accept': 'application/json',
        'Authorization': 'Bearer $tokenStr'
      };

      final responseData = await NetworkApiServices().postApi(
        data,
        AppUrls.changePhoneUrl,
        LocalKeys.signUp,
        headers: headers
      );

      if (responseData != null) {
        if (responseData['status'] == 'success' || responseData['message'] != null) {
          otpSent.value = true;
          otpLoading.value = false;
          phoneController.text = fullPhone;
          "Code envoyé sur WhatsApp !".showToast();
        } else {
           otpLoading.value = false;
           "Erreur d'envoi du code".showToast();
        }
      } else {
        otpLoading.value = false;
      }
    } catch (e) {
      otpLoading.value = false;
      "Une erreur est survenue lors de l'envoi du message WhatsApp".showToast();
    }
  }

  Future<void> verifyPhoneOtp(BuildContext context) async {
    final code = otpController.text.trim();
    if (code.isEmpty) {
      "Veuillez entrer le code de validation".showToast();
      return;
    }
    final localPhone = phoneController.text.trim();
    final dialCode = phone.value?.dialCode ?? '225';
    final fullPhone = localPhone.startsWith("+") ? localPhone : "+$dialCode$localPhone";

    otpLoading.value = true;
    try {
      final data = {
        'phone': fullPhone,
        'otp_verify_status': '1',
        'otp': code,
      };
      final tokenStr = Provider.of<SignUpService>(context, listen: false).token;
      final headers = {
        'Accept': 'application/json',
        'Authorization': 'Bearer $tokenStr'
      };

      final responseData = await NetworkApiServices().postApi(
        data,
        AppUrls.changePhoneUrl,
        LocalKeys.signUp,
        headers: headers
      );

      if (responseData != null && responseData['message'] == 'Phone Number Changed Successfully.') {
        isPhoneVerified.value = true;
        otpSent.value = false;
        otpLoading.value = false;
        otpController.clear();
        
        phoneController.text = fullPhone;
        "Numéro de téléphone vérifié avec succès !".showToast();
      } else if (responseData != null && responseData.containsKey("message")) {
        otpLoading.value = false;
        responseData["message"]?.toString().showToast();
      } else {
        otpLoading.value = false;
        "Code incorrect ou expiré. Veuillez réessayer.".showToast();
      }
    } catch (e) {
      otpLoading.value = false;
      "Code incorrect ou expiré. Veuillez réessayer.".showToast();
    }
  }
}
