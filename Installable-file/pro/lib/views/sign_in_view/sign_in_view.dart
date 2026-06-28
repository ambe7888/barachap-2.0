import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/views/sign_in_view/components/email_sign_in.dart';
import 'package:prohand/views/sign_in_view/components/social_sign_in_button.dart';

import '../sign_in_with_otp_view/sign_in_with_otp_view.dart';
import 'components/create_account.dart';
import 'components/social_sign_in.dart';

class SignInView extends StatelessWidget {
  const SignInView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const SizedBox(),
        leadingWidth: 12,
        title: Text(LocalKeys.signIn),
      ),
      body: Scrollbar(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            children: [
              const EmailSignIn(),
              12.toHeight,
              SocialSignInButton(
                  title: LocalKeys.otpSignIn,
                  image: null,
                  onTap: () {
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
