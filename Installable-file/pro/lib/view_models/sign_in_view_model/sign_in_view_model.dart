// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/auth_services/sign_up_service.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/views/landing_view/landing_view.dart';
import 'package:prohand/views/sign_up_view/sign_up_name_date.dart';
import 'package:provider/provider.dart';

import '../../customization.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../services/auth_services/email_otp_service.dart';
import '../../services/auth_services/sign_in_service.dart';
import '../../services/chat_services/chat_credential_service.dart';
import '../../services/profile_services/profile_info_service.dart';
import '../../services/push_notification_service.dart';
import '../../views/reset_password/enter_otp_view.dart';

class SignInViewModel {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();

  final ValueNotifier<bool> passObs = ValueNotifier(true);
  final ValueNotifier<bool> passConfirmObs = ValueNotifier(true);
  final ValueNotifier<bool> rememberPass = ValueNotifier(true);

  final GlobalKey<FormState> formKey = GlobalKey();

  final ValueNotifier loading = ValueNotifier<bool>(false);
  final ValueNotifier li = ValueNotifier<bool>(false);
  bool signInSuccess = false;

  SignInViewModel._init();
  static SignInViewModel? _instance;
  static SignInViewModel get instance {
    _instance ??= SignInViewModel._init();
    return _instance!;
  }

  SignInViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryEmailSignIn(BuildContext context) {}
  setUserInfo({email, pass}) async {
    if (emailController.text.isNotEmpty &&
        passwordController.text.isNotEmpty &&
        rememberPass.value) {
      sPref?.setString("user-email", emailController.text);
      sPref?.setString("user-pass", passwordController.text);
    } else if (rememberPass.value) {
      sPref?.setString("user-email", email ?? "");
      sPref?.setString("user-pass", pass ?? "");
    } else {
      sPref?.setString("user-email", "");
      sPref?.setString("user-pass", "");
    }
    sPref?.setBool("user-remember", rememberPass.value);
  }

  emailUsernameValidator(value) {
    if (value == null || value.isEmpty || value.trim().isEmpty) {
      return asProvider.getString(LocalKeys.emailUsernameValidateText);
    }
    return null;
  }

  initSavedInfo() {
    if (siteLink.contains("prohandy.xgenious.com")) {
      emailController.text =
          sPref?.getString("user-email") ?? "provider@gmail.com";
      passwordController.text = sPref?.getString("user-pass") ?? "12345678";
      rememberPass.value = sPref?.getBool("user-remember") ?? true;
    } else {
      emailController.text = sPref?.getString("user-email") ?? "";
      passwordController.text = sPref?.getString("user-pass") ?? "";
      rememberPass.value = sPref?.getBool("user-remember") ?? true;
    }
  }

  signIn(BuildContext context) async {
    final isValid = formKey.currentState?.validate();

    if (isValid == false) {
      return;
    }

    loading.value = true;
    final siProvider = Provider.of<SignInService>(context, listen: false);
    final piProvider = Provider.of<ProfileInfoService>(context, listen: false);
    siProvider
        .trySignIn(
            emailUsername: emailController.text,
            password: passwordController.text)
        .then((value) async {
      final si = Provider.of<SignInService>(context, listen: false);
      debugPrint("Sign in result is $value".toString());
      if (value == true) {
        if (si.firstName == null) {
          Provider.of<SignUpService>(context, listen: false).sToken(si.token);
          await context.toPage(const SignUpNameDate());
          loading.value = false;
          return;
        }
        LocalKeys.signedInSuccessfully.showToast();
        setUserInfo();
        setToken(si.token);
        Provider.of<ChatCredentialService>(context, listen: false)
            .fetchCredentials();
        signInSuccess = true;
        await PushNotificationService().updateDeviceToken(forceUpdate: true);
        await piProvider.fetchProfileInfo();
        Provider.of<DashboardInfoService>(context, listen: false)
            .fetchDashboardInfo();
        loading.value = false;
        li.value = true;
        context.toUntilPage(const LandingView());
      } else if (value == false) {
        final result =
            await Provider.of<EmailManageService>(context, listen: false)
                .tryOtpToEmail(emailUsername: emailController.text);
        if (result == true) {
          final otpResult = await Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => EnterOtpView(
                    Provider.of<EmailManageService>(context, listen: false).otp,
                    email: emailController.text,
                    fromRegister: true),
              ));
          if (otpResult != true) {
            debugPrint(otpResult.toString());
          } else {
            final verifyResult =
                await Provider.of<EmailManageService>(context, listen: false)
                    .tryEmailVerify(
                        emailUsername: emailController.text,
                        userId: si.userId,
                        token: si.token);
            if (verifyResult == true) {
              Provider.of<SignUpService>(context, listen: false)
                  .sToken(si.token);
              await context.toPage(const SignUpNameDate());
            }
          }
        }
        loading.value = false;
      } else {
        loading.value = false;
      }
    });
  }
}
