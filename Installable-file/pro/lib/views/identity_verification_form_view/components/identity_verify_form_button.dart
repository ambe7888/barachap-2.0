import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/identity_verify_view_model/identity_verify_view_model.dart';

class IdentityVerifyFormButton extends StatelessWidget {
  const IdentityVerifyFormButton({super.key});

  @override
  Widget build(BuildContext context) {
    final ivm = IVViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ValueListenableBuilder(
        valueListenable: ivm.isLoading,
        builder: (context, value, child) => CustomButton(
          onPressed: () {
            if (!(ivm.formKey.currentState!.validate())) {
              return;
            }
            if (ivm.frontImage.value == null || ivm.backImage.value == null) {
              LocalKeys.selectDocumentImages.showToast();
              return;
            }
            ivm.submitForIV(context);
          },
          btText: LocalKeys.submitId,
          isLoading: value,
        ),
      ),
    );
  }
}
