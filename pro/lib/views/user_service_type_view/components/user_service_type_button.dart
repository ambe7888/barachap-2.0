import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/profile_edit_view_model/profile_edit_view_model.dart';

class UserServiceTypeButton extends StatelessWidget {
  const UserServiceTypeButton({super.key});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ValueListenableBuilder(
          valueListenable: pem.isLoading,
          builder: (context, value, child) {
            return CustomButton(
              onPressed: () {
                if (pem.categories.value.isEmpty) {
                  LocalKeys.selectCategory.showToast();
                  return;
                }
                pem.updateServiceType(context);
              },
              btText: LocalKeys.saveChanges,
              isLoading: value,
            );
          }),
    );
  }
}
