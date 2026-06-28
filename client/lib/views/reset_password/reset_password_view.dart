import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:provider/provider.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import '../../services/otp_service.dart';
import '/helper/extension/string_extension.dart';
import '/utils/components/custom_button.dart';
import '/view_models/reset_password_model/reset_password_model.dart';

import '../../helper/local_keys.g.dart';
import '../../utils/components/empty_spacer_helper.dart';
import '../../utils/components/field_with_label.dart';

class ResetPasswordView extends StatelessWidget {
  static const routeName = 'reset_password_view';
  const ResetPasswordView({super.key});

  @override
  Widget build(BuildContext context) {
    final rp = ResetPasswordViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => OtpService(),
      child: Scaffold(
        backgroundColor: context.color.backgroundColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.enterEmail.capitalizeWords),
        ),
        body: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Align(
                alignment: Alignment.center,
                child: SizedBox(
                  height: 220,
                  child: LottieBuilder.asset(
                    "assets/animations/forgot_pass.json",
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
                  key: rp.emailFormKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      FieldWithLabel(
                        label: LocalKeys.email,
                        hintText: LocalKeys.enterEmail,
                        keyboardType: TextInputType.emailAddress,
                        controller: rp.emailController,
                        validator: (value) {
                          if (value!.validateEmail) {
                            return null;
                          }
                          return LocalKeys.enterValidEmailAddress;
                        },
                      ),
                      12.toHeight,
                      Consumer<OtpService>(
                        builder: (context, otpProvider, child) {
                          return CustomButton(
                              btText: LocalKeys.sendVerificationCode,
                              isLoading: otpProvider.loadingSendOTP,
                              onPressed: () async {
                                FocusScope.of(context).unfocus();
                                rp.trySendingOTP(context);
                              });
                        },
                      )
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
