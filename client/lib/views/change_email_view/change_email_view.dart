import 'package:flutter/material.dart';

import '../../utils/components/custom_button.dart';
import './../../helper/extension/context_extension.dart';
import './../../helper/extension/int_extension.dart';
import './../../helper/extension/string_extension.dart';
import './../../helper/local_keys.g.dart';
import './../../utils/components/field_with_label.dart';
import './../../utils/components/navigation_pop_icon.dart';
import './../../view_models/change_email_phone_view_model/change_email_phone_view_model.dart';

class ChangeEmailView extends StatelessWidget {
  const ChangeEmailView({super.key});

  @override
  Widget build(BuildContext context) {
    final cep = ChangeEmailPhoneViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.changeEmail),
      ),
      body: SafeArea(
        child: Container(
            width: double.infinity,
            margin: const EdgeInsets.only(top: 8, bottom: 2),
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Form(
              key: cep.mailForm,
              child: ValueListenableBuilder<bool>(
                valueListenable: cep.isEmailVerified,
                builder: (context, isVerified, child) {
                  if (isVerified) {
                    return Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Center(
                          child: SizedBox(
                            height: 100,
                            child: const Icon(Icons.check_circle, color: Colors.green, size: 80),
                          ),
                        ),
                        24.toHeight,
                        Center(
                          child: Text(
                            "E-mail vérifié avec succès",
                            style: context.titleLarge?.copyWith(color: Colors.green, fontWeight: FontWeight.bold),
                            textAlign: TextAlign.center,
                          ),
                        ),
                        24.toHeight,
                        FieldWithLabel(
                          label: LocalKeys.email,
                          hintText: LocalKeys.enterNewEmail,
                          controller: cep.emailController,
                          isRequired: true,
                          isSuccess: true,
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
                          child: "verification".toAImage(fit: BoxFit.contain),
                        ),
                      ),
                      24.toHeight,
                      Center(
                        child: Text(
                          LocalKeys.verificationCodeWillBeSentToTheNewEmail,
                          style: context.bodyMedium,
                          textAlign: TextAlign.center,
                        ),
                      ),
                      24.toHeight,
                      FieldWithLabel(
                        label: LocalKeys.email,
                        hintText: LocalKeys.enterNewEmail,
                        controller: cep.emailController,
                        isRequired: true,
                        isSuccess: false,
                        validator: (value) {
                          if (!value.toString().validateEmail) {
                            return LocalKeys.invalidEmailAddress;
                          }
                          return null;
                        },
                      )
                    ],
                  );
                }
              ),
            )),
      ),
      bottomNavigationBar: ValueListenableBuilder<bool>(
        valueListenable: cep.isEmailVerified,
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
            child: ValueListenableBuilder(
              valueListenable: cep.isLoading,
              builder: (context, value, child) => CustomButton(
                onPressed: () {
                  cep.tryChangingEmail(context);
                  context.unFocus;
                },
                btText: LocalKeys.sendVerificationCode,
                isLoading: value,
              ),
            ),
          );
        }
      ),
    );
  }
}
