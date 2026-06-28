import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/sign_in_with_otp_view_model/sign_in_with_otp_view_model.dart';
import 'package:prohandy_client/views/sign_in_view/components/email_sign_in.dart';
import 'package:prohandy_client/views/sign_in_view/components/social_sign_in_button.dart';

import '../sign_in_with_otp_view/sign_in_with_otp_view.dart';
import 'components/create_account.dart';
import 'components/social_sign_in.dart';

class SignInView extends StatelessWidget {
  const SignInView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.backgroundColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.signIn),
      ),
      body: Scrollbar(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
          child: Column(
            children: [
              Container(
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
                child: const EmailSignIn(),
              ),
              12.toHeight,
              SocialSignInButton(
                  title: LocalKeys.otpSignIn,
                  image: null,
                  onTap: () {
                    SignInWithOtpViewModel.dispose;
                    context.toPage(const SignInWithOtpView());
                  }),
              24.toHeight,
              const CreateAccount(),
              24.toHeight,
              Row(
                children: [
                  Expanded(
                    flex: 1,
                    child: const SizedBox().divider,
                  ),
                  Padding(
                    padding: 6.paddingH,
                    child: Text(
                      LocalKeys.or.toUpperCase(),
                      style: context.titleSmall?.bold,
                    ),
                  ),
                  Expanded(
                    flex: 1,
                    child: const SizedBox().divider,
                  ),
                ],
              ),
              24.toHeight,
              const SocialSignIn()
            ],
          ),
        ),
      ),
    );
  }
}
