import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../identity_verification_form_view/identity_verification_form_view.dart';

class IdentityVerifyButton extends StatelessWidget {
  const IdentityVerifyButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: CustomButton(
        onPressed: () {
          context.toPage(const IdentityVerificationFormView());
        },
        btText: LocalKeys.verifyYourIdentity,
      ),
    );
  }
}
