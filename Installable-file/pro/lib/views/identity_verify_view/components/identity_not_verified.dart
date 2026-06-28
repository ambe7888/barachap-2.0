import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

class IdentityNotVerified extends StatelessWidget {
  const IdentityNotVerified({super.key});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          16.toHeight,
          Padding(
            padding: 32.paddingV,
            child: SvgPicture.asset("assets/svg/identity-verification.svg"),
          ),
          Text(
            LocalKeys.verifyYourIdentity,
            style: context.headlineLarge?.bold,
          ),
          8.toHeight,
          SizedBox(
            width: context.width / 1.4,
            child: Text(
              LocalKeys.verifyYourIdentityDescription,
              textAlign: TextAlign.center,
              style: context.bodySmall
                  ?.copyWith(color: context.color.tertiaryContrastColo),
            ),
          )
        ],
      ),
    );
  }
}
