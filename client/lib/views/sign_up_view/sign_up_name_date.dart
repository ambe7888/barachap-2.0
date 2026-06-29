import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/sign_up_view_model/sign_up_view_model.dart';
import 'package:prohandy_client/helper/phone_field.dart';

import 'upload_profile_image_view.dart';

class SignUpNameDate extends StatelessWidget {
  const SignUpNameDate({super.key});

  @override
  Widget build(BuildContext context) {
    final sum = SignUpViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
      ),
      body: SingleChildScrollView(
          child: Form(
        key: sum.piFormKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            12.toHeight,
            Text(
              LocalKeys.aboutYou,
              style: context.labelLarge?.bold,
            ),
            4.toHeight,
            Text(
              LocalKeys.aboutYouDesc,
              style: context.bodyMedium,
            ),
            32.toHeight,
            FieldWithLabel(
              label: LocalKeys.firstName,
              hintText: LocalKeys.enterFirstName,
              controller: sum.fNameController,
              isRequired: true,
              validator: (value) {
                if ((value ?? "").isEmpty) {
                  return LocalKeys.enterAValidName;
                }
                return null;
              },
            ),
            FieldWithLabel(
              label: LocalKeys.lastName,
              hintText: LocalKeys.enterLastName,
              controller: sum.lNameController,
              isRequired: true,
              validator: (value) {
                if ((value ?? "").isEmpty) {
                  return LocalKeys.enterAValidName;
                }
                return null;
              },
            ),
            ValueListenableBuilder<bool>(
              valueListenable: sum.isPhoneVerified,
              builder: (context, verified, child) {
                return Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (verified)
                      FieldWithLabel(
                        label: LocalKeys.phone,
                        hintText: "",
                        controller: sum.phoneController,
                        isRequired: true,
                        readOnly: true,
                      )
                    else
                      PhoneField(
                        phone: sum.phone,
                        controller: sum.phoneController,
                        label: LocalKeys.phone,
                        hintText: "Ex: 0102030405",
                        isRequired: true,
                      ),
                    if (!verified) ...[
                      ValueListenableBuilder<bool>(
                        valueListenable: sum.otpSent,
                        builder: (context, sent, child) {
                          if (!sent) {
                            return ValueListenableBuilder<bool>(
                              valueListenable: sum.otpLoading,
                              builder: (context, loading, child) {
                                return SizedBox(
                                  width: double.infinity,
                                  child: OutlinedButton(
                                    onPressed: loading ? null : () => sum.sendOtpToPhone(context),
                                    child: loading
                                        ? const SizedBox(
                                            height: 20,
                                            width: 20,
                                            child: CircularProgressIndicator(strokeWidth: 2),
                                          )
                                        : const Text("Envoyer le code par SMS"),
                                  ),
                                );
                              },
                            );
                          } else {
                            return Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                12.toHeight,
                                FieldWithLabel(
                                  label: "Code de validation",
                                  hintText: "Entrez le code à 6 chiffres",
                                  controller: sum.otpController,
                                  isRequired: true,
                                ),
                                8.toHeight,
                                ValueListenableBuilder<bool>(
                                  valueListenable: sum.otpLoading,
                                  builder: (context, loading, child) {
                                    return Row(
                                      children: [
                                        Expanded(
                                          child: ElevatedButton(
                                            onPressed: loading ? null : () => sum.verifyPhoneOtp(context),
                                            child: loading
                                                ? const SizedBox(
                                                    height: 20,
                                                    width: 20,
                                                    child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                                                  )
                                                : const Text("Confirmer le code"),
                                          ),
                                        ),
                                        12.toWidth,
                                        OutlinedButton(
                                          onPressed: loading ? null : () => sum.sendOtpToPhone(context),
                                          child: const Text("Renvoyer"),
                                        ),
                                      ],
                                    );
                                  },
                                ),
                              ],
                            );
                          }
                        },
                      ),
                    ] else ...[
                      Row(
                        children: [
                          const Icon(Icons.check_circle, color: Colors.green),
                          8.toWidth,
                          const Text(
                            "Numéro vérifié avec succès",
                            style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                    ],
                    16.toHeight,
                  ],
                );
              },
            ),
            ValueListenableBuilder<bool>(
              valueListenable: sum.isPhoneVerified,
              builder: (context, verified, child) {
                return SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                      onPressed: !verified
                          ? null
                          : () {
                              if (!(sum.piFormKey.currentState!.validate())) return;
                              context.toPage(const UploadProfileImageView());
                            },
                      child: Text(
                        LocalKeys.continueO,
                      )),
                );
              },
            ),
          ],
        ).hp20,
      )),
    );
  }
}
