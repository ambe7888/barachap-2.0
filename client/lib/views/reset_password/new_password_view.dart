import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';

import '/helper/local_keys.g.dart';
import '/view_models/reset_password_model/reset_password_model.dart';
import '../../helper/svg_assets.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/pass_field_with_label.dart';

class NewPasswordView extends StatelessWidget {
  final otp;
  const NewPasswordView({super.key, required this.otp});

  @override
  Widget build(BuildContext context) {
    final rp = ResetPasswordViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.backgroundColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.resetPassword.capitalizeWords),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: SizedBox(
                height: 200,
                child: LottieBuilder.asset(
                  "assets/animations/lock.json",
                  fit: BoxFit.contain,
                ),
              ),
            ),
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: context.color.accentContrastColor,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(
                  color: context.color.primaryBorderColor.withOpacity(0.6),
                  width: 1,
                ),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.02),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Form(
                  key: rp.passwordFormKey,
                  child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        PassFieldWithLabel(
                          label: LocalKeys.newPassword,
                          hintText: LocalKeys.enterPassword,
                          valueListenable: rp.obscurePassNew,
                          keyboardType: TextInputType.visiblePassword,
                          textInputAction: TextInputAction.next,
                          controller: rp.newPassController,
                          svgPrefix: SvgAssets.lock,
                        ),
                        PassFieldWithLabel(
                          label: LocalKeys.confirmPassword,
                          hintText: LocalKeys.retypePassword,
                          valueListenable: rp.obscurePassCon,
                          keyboardType: TextInputType.visiblePassword,
                          textInputAction: TextInputAction.next,
                          controller: TextEditingController(),
                          svgPrefix: SvgAssets.lock,
                          validator: (value) {
                            if (rp.newPassController.text != value) {
                              return LocalKeys.passwordDidNotMatch;
                            }
                            return null;
                          },
                        ),
                      ])),
            ),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ValueListenableBuilder(
          valueListenable: rp.loadingResetPassword,
          builder: (context, value, child) => CustomButton(
            onPressed: () {
              rp.tryResetPassword(context, otp);
            },
            btText: LocalKeys.setNewPassword,
            isLoading: value,
          ),
        ),
      ),
    );
  }
}
