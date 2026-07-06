import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/phone_field.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:provider/provider.dart';

import '../../services/auth_services/phone_manage_service.dart';
import './../../services/auth_services/email_otp_service.dart';
import './../../views/change_email_view/change_email_otp_view.dart';
import './../../views/change_phone_view/change_phone_otp_view.dart';

class ChangeEmailPhoneViewModel {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final ValueNotifier<Phone?> phone = ValueNotifier(null);
  final ValueNotifier<bool> isEmailVerified = ValueNotifier(false);
  final ValueNotifier<bool> isPhoneVerified = ValueNotifier(false);

  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<bool> isWhatsappLoading = ValueNotifier(false);
  final ValueNotifier<bool> isSmsLoading = ValueNotifier(false);

  final GlobalKey<FormState> mailForm = GlobalKey();

  ChangeEmailPhoneViewModel._init();
  static ChangeEmailPhoneViewModel? _instance;
  static ChangeEmailPhoneViewModel get instance {
    _instance ??= ChangeEmailPhoneViewModel._init();
    return _instance!;
  }

  ChangeEmailPhoneViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryChangingEmail(BuildContext context) async {
    if (mailForm.currentState!.validate() != true) return;
    isLoading.value = true;
    try {
      final otpResult =
          await Provider.of<EmailManageService>(context, listen: false)
              .tryOtpToNewEmail(emailUsername: emailController.text);
      if (otpResult == true) {
        final verifyResult = await Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => ChangeEmailOtpView(null),
            ));
        if (verifyResult == true) {
          isEmailVerified.value = true;
          context.pop;
        }
      }
    } catch (e) {
      debugPrint(e.toString());
    } finally {
      isLoading.value = false;
    }
  }

  void tryChangingPhone(BuildContext context, {String channel = 'whatsapp'}) async {
    final localPhone = phoneController.text.trim();
    if (localPhone.isEmpty) {
      return;
    }
    if (channel == 'sms') {
      isSmsLoading.value = true;
    } else {
      isWhatsappLoading.value = true;
    }
    try {
      final dialCode = phone.value?.dialCode ?? "225";
      final fullPhone = "+$dialCode$localPhone";
      
      final pm = Provider.of<PhoneManageService>(context, listen: false);
      pm.firebaseVerificationId = null; // reset

      if (channel == 'sms') {
        final otpResult = await pm.tryFirebaseOtp(phone: fullPhone, context: context);
        if (otpResult == true) {
          final verifyResult = await Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => ChangePhoneOtpView(),
              ));
          if (verifyResult == true) {
            isPhoneVerified.value = true;
            await Provider.of<ProfileInfoService>(context, listen: false).fetchProfileInfo();
          }
        }
      } else {
        final otpResult = await pm.trySendingOtpToNewPhone(phone: fullPhone);
        if (otpResult == true) {
          final verifyResult = await Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => ChangePhoneOtpView(),
              ));
          if (verifyResult == true) {
            isPhoneVerified.value = true;
            await Provider.of<ProfileInfoService>(context, listen: false).fetchProfileInfo();
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
