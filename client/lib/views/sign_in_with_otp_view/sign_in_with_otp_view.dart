import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/phone_field.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/sign_in_with_otp_view_model/sign_in_with_otp_view_model.dart';

class SignInWithOtpView extends StatelessWidget {
  const SignInWithOtpView({super.key});

  @override
  Widget build(BuildContext context) {
    final sio = SignInWithOtpViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        child: Form(
          key: sio.formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: SizedBox(
                  height: 160,
                  child: "verification".toAImage(fit: BoxFit.contain),
                ),
              ),
              24.toHeight,
              Center(
                child: Text(
                  "Saisissez votre numéro de téléphone pour recevoir un code de validation OTP par WhatsApp.",
                  style: context.bodyMedium,
                  textAlign: TextAlign.center,
                ),
              ),
              24.toHeight,
              PhoneField(
                phone: sio.phone,
                controller: sio.phoneController,
                label: LocalKeys.phone,
                hintText: "0102030405",
                isSuccess: false,
              ),
              24.toHeight,
              Column(
                children: [
                  ValueListenableBuilder<bool>(
                    valueListenable: sio.isWhatsappLoading,
                    builder: (context, val, child) => CustomButton(
                      onPressed: () {
                        sio.trySignIn(context, channel: 'whatsapp');
                      },
                      btText: "Envoyer par WhatsApp",
                      isLoading: val,
                    ),
                  ),
                  12.toHeight,
                  ValueListenableBuilder<bool>(
                    valueListenable: sio.isSmsLoading,
                    builder: (context, val, child) => CustomButton(
                      onPressed: () {
                        sio.trySignIn(context, channel: 'sms');
                      },
                      btText: "Envoyer par SMS",
                      isLoading: val,
                      backgroundColor: Colors.blueAccent,
                    ),
                  ),
                ],
              )
            ],
          ),
        ),
      ),
    );
  }
}
