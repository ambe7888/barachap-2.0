import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_button.dart';

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
              onPressed: () {},
              btText: LocalKeys.signIn,
              isLoading: false,
            ),
          )
        ],
      ),
    );
  }
}
