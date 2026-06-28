import 'dart:io';

import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/empty_spacer_helper.dart';
import 'package:prohand/view_models/sign_in_view_model/social_sign_in_view_model.dart';

import '../../sign_in_view/components/social_sign_in_button.dart';

class SocialSignUp extends StatelessWidget {
  const SocialSignUp({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final ssi = SocialSignInViewModel.instance;
    return Column(
      children: [
        SocialSignInButton(
          title: LocalKeys.signUpWithGoogle,
          image: "google",
          onTap: () async {
            await ssi.trySocialSignIn(context);
          },
        ),
        EmptySpaceHelper.emptyHeight(16),
        SocialSignInButton(
          title: LocalKeys.signUpWithFacebook,
          image: "facebook",
          onTap: () async {
            await ssi.trySocialSignIn(context, type: "facebook");
          },
        ),
        if (Platform.isIOS) ...[
          EmptySpaceHelper.emptyHeight(16),
          SocialSignInButton(
            title: LocalKeys.signUpWithApple,
            image: "apple",
            onTap: () async {
              await ssi.trySocialSignIn(context, type: "apple");
            },
          ),
        ],
      ],
    );
  }
}
