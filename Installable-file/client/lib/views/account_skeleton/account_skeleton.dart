import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';

import '../../view_models/sign_in_view_model/sign_in_view_model.dart';
import '../sign_in_view/sign_in_view.dart';

class AccountSkeleton extends StatelessWidget {
  const AccountSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      alignment: Alignment.center,
      color: context.color.accentContrastColor,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          LottieBuilder.asset(
            "assets/animations/sign_in.json",
            fit: BoxFit.cover,
            repeat: false,
          ),
          SizedBox(
            width: 300,
            child: CustomButton(
              onPressed: () {
                SignInViewModel.dispose;
                SignInViewModel.instance.initSavedInfo();
                context.toPage(const SignInView());
              },
              btText: LocalKeys.signIn,
              isLoading: false,
            ),
          )
        ],
      ),
    );
  }
}
