import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/sign_up_view/components/email_sign_up.dart';
import 'package:prohandy_client/views/sign_up_view/components/sign_in_to_account.dart';

import 'components/social_sign_up.dart';

class SignUpView extends StatelessWidget {
  const SignUpView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.signUp),
      ),
      body: Scrollbar(
          child: SingleChildScrollView(
        padding: 8.paddingV,
        child: Container(
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            children: [
              const EmailSignUp(),
              24.toHeight,
              const SignInToAccount(),
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
              const SocialSignUp()
            ],
          ),
        ),
      )),
    );
  }
}
