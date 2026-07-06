import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/views/landing_view/landing_view.dart';
import 'package:provider/provider.dart';

import '../../helper/phone_field.dart';
import '../../services/auth_services/phone_manage_service.dart';
import '../../services/profile_services/profile_info_service.dart';
import '../../views/sign_in_with_otp_view/phone_otp_view.dart';

class SignInWithOtpViewModel {
  final TextEditingController phoneController = TextEditingController();

  final ValueNotifier<Phone?> phone = ValueNotifier(null);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<bool> isWhatsappLoading = ValueNotifier(false);
  final ValueNotifier<bool> isSmsLoading = ValueNotifier(false);

  final GlobalKey<FormState> formKey = GlobalKey();

  SignInWithOtpViewModel._init();
  static SignInWithOtpViewModel? _instance;
  static SignInWithOtpViewModel get instance {
    _instance ??= SignInWithOtpViewModel._init();
    return _instance!;
  }

  SignInWithOtpViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void trySignIn(BuildContext context, {String channel = 'whatsapp'}) async {
    if (formKey.currentState!.validate() != true) return;
    if (channel == 'sms') {
      isSmsLoading.value = true;
    } else {
      isWhatsappLoading.value = true;
    }
    try {
      final dialCode = phone.value?.dialCode ?? "225";
      final fullPhone = "+$dialCode${phoneController.text}";
      
      final pm = Provider.of<PhoneManageService>(context, listen: false);
      pm.firebaseVerificationId = null; // reset

      if (channel == 'sms') {
        final otpResult = await pm.tryFirebaseOtp(phone: fullPhone, context: context);
        if (otpResult == true) {
          final vResult = await Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => PhoneOtpView(),
              ));
          if (vResult == true) {
            await Provider.of<ProfileInfoService>(context, listen: false)
                .fetchProfileInfo();
            context.toUntilPage(const LandingView());
          }
        }
      } else {
        final otpResult = await pm.tryOtpToPhone(phone: fullPhone);
        if (otpResult == true) {
          final vResult = await Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => PhoneOtpView(),
              ));
          if (vResult == true) {
            await Provider.of<ProfileInfoService>(context, listen: false)
                .fetchProfileInfo();
            context.toUntilPage(const LandingView());
          }
        }
      }
    } catch (e) {
      debugPrint(e.toString());
    } finally {
      isWhatsappLoading.value = false;
      isSmsLoading.value = false;
    }
  }
}
