import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/phone_field.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../../view_models/change_email_phone_view_model/change_email_phone_view_model.dart';
import '../../../view_models/onboarding_wizard_view_model/onboarding_wizard_view_model.dart';

class PhoneStep extends StatelessWidget {
  const PhoneStep({super.key});

  @override
  Widget build(BuildContext context) {
    // We can reuse the ChangeEmailPhoneViewModel for handling phone logic
    final cep = ChangeEmailPhoneViewModel.instance;
    final wizardViewModel = Provider.of<OnboardingWizardViewModel>(context, listen: false);

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "Vérification du numéro",
            style: context.headlineMedium?.copyWith(
              fontWeight: FontWeight.bold,
              color: context.color.primaryContrastColor,
            ),
          ),
          16.toHeight,
          Text(
            "Ajoutez votre numéro de téléphone pour sécuriser votre compte et permettre aux clients de vous contacter.",
            style: context.bodyLarge?.copyWith(
              color: context.color.secondaryContrastColor,
            ),
          ),
          48.toHeight,
          ValueListenableBuilder<bool>(
            valueListenable: cep.isPhoneVerified,
            builder: (context, isVerified, child) {
              if (isVerified) {
                return Column(
                  children: [
                    const Center(
                      child: Icon(Icons.check_circle, color: Colors.green, size: 80),
                    ),
                    24.toHeight,
                    Center(
                      child: Text(
                        "Numéro vérifié avec succès",
                        style: context.titleLarge?.copyWith(color: Colors.green, fontWeight: FontWeight.bold),
                        textAlign: TextAlign.center,
                      ),
                    ),
                    32.toHeight,
                    CustomButton(
                      onPressed: () => wizardViewModel.nextStep(context),
                      btText: "Étape suivante",
                      isLoading: false,
                    ),
                  ],
                );
              }

              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  PhoneField(
                    phone: cep.phone,
                    controller: cep.phoneController,
                    label: LocalKeys.phone,
                    hintText: "Ex: 0102030405",
                  ),
                  32.toHeight,
                  ValueListenableBuilder<bool>(
                    valueListenable: cep.isWhatsappLoading,
                    builder: (context, val, child) => CustomButton(
                      onPressed: () {
                        // This uses the existing logic which navigates to ChangePhoneOtpView
                        // It's acceptable if the OTP view itself is simple, or we can customize it later.
                        cep.tryChangingPhone(context, channel: 'whatsapp');
                      },
                      btText: "Vérifier via WhatsApp",
                      isLoading: val,
                      backgroundColor: Colors.green,
                      icon: const Icon(Icons.wechat, color: Colors.white),
                    ),
                  ),
                  16.toHeight,
                  ValueListenableBuilder<bool>(
                    valueListenable: cep.isSmsLoading,
                    builder: (context, val, child) => CustomButton(
                      onPressed: () {
                        cep.tryChangingPhone(context, channel: 'sms');
                      },
                      btText: "Vérifier via SMS",
                      isLoading: val,
                      backgroundColor: primaryColor,
                      icon: const Icon(Icons.message, color: Colors.white),
                    ),
                  ),
                ],
              );
            },
          ),
        ],
      ),
    );
  }
}
