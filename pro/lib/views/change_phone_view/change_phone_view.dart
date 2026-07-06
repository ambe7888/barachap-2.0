import 'package:flutter/material.dart';

import '../../utils/components/custom_button.dart';
import './../../helper/extension/context_extension.dart';
import './../../helper/extension/int_extension.dart';
import './../../helper/local_keys.g.dart';
import './../../helper/phone_field.dart';
import './../../helper/extension/string_extension.dart';
import './../../utils/components/navigation_pop_icon.dart';
import './../../view_models/change_email_phone_view_model/change_email_phone_view_model.dart';
import '../../helper/image_assets.dart';

class ChangePhoneView extends StatelessWidget {
  const ChangePhoneView({super.key});

  @override
  Widget build(BuildContext context) {
    final cep = ChangeEmailPhoneViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.changePhone),
      ),
      body: SafeArea(
        child: Container(
            width: double.infinity,
            margin: const EdgeInsets.only(top: 8, bottom: 2),
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: SingleChildScrollView(
              child: ValueListenableBuilder<bool>(
                valueListenable: cep.isPhoneVerified,
                builder: (context, isVerified, child) {
                  if (isVerified) {
                    return Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Center(
                          child: SizedBox(
                            height: 100,
                            child: Icon(Icons.check_circle, color: Colors.green, size: 80),
                          ),
                        ),
                        24.toHeight,
                        Center(
                          child: Text(
                            "Numéro vérifié avec succès",
                            style: context.titleLarge?.copyWith(color: Colors.green, fontWeight: FontWeight.bold),
                            textAlign: TextAlign.center,
                          ),
                        ),
                        24.toHeight,
                        PhoneField(
                          phone: cep.phone,
                          controller: cep.phoneController,
                          label: LocalKeys.phone,
                          hintText: "0102030405",
                        )
                      ],
                    );
                  }
                  
                  return Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Center(
                        child: SizedBox(
                          height: 160,
                          child: ImageAssets.verification.toAImage(fit: BoxFit.contain),
                        ),
                      ),
                      24.toHeight,
                      Center(
                        child: Text(
                          "Saisissez votre nouveau numéro de téléphone pour recevoir un code de validation OTP.",
                          style: context.bodyMedium,
                          textAlign: TextAlign.center,
                        ),
                      ),
                      24.toHeight,
                      PhoneField(
                        phone: cep.phone,
                        controller: cep.phoneController,
                        label: LocalKeys.phone,
                        hintText: "0102030405",
                      )
                    ],
                  );
                }
              ),
            ),
        ),
      ),
      bottomNavigationBar: ValueListenableBuilder<bool>(
        valueListenable: cep.isPhoneVerified,
        builder: (context, isVerified, child) {
          if (isVerified) {
            return const SizedBox.shrink();
          }
          return Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            decoration: BoxDecoration(
                color: context.color.accentContrastColor,
                border: Border(
                    top: BorderSide(color: context.color.primaryBorderColor))),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                ValueListenableBuilder<bool>(
                  valueListenable: cep.isWhatsappLoading,
                  builder: (context, val, child) => CustomButton(
                    onPressed: () {
                      cep.tryChangingPhone(context, channel: 'whatsapp');
                    },
                    btText: "Envoyer par WhatsApp",
                    isLoading: val,
                  ),
                ),
                12.toHeight,
                ValueListenableBuilder<bool>(
                  valueListenable: cep.isSmsLoading,
                  builder: (context, val, child) => CustomButton(
                    onPressed: () {
                      cep.tryChangingPhone(context, channel: 'sms');
                    },
                    btText: "Envoyer par SMS",
                    isLoading: val,
                    backgroundColor: Colors.blueAccent,
                  ),
                ),
              ],
            ),
          );
        }
      ),
    );
  }
}
