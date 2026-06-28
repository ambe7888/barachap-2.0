import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/pass_field_with_label.dart';
import 'package:prohand/view_models/change_password_view_model/change_password_view_model.dart';

import '../../utils/components/custom_button.dart';

class ChangePasswordView extends StatelessWidget {
  const ChangePasswordView({super.key});

  @override
  Widget build(BuildContext context) {
    final cpm = ChangePasswordViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.changePassword),
      ),
      body: SafeArea(
        child: Container(
          width: double.infinity,
          margin: const EdgeInsets.only(top: 8, bottom: 2),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.cardFillColor,
          child: AutofillGroup(
            child: Form(
              key: cpm.formKey,
              child: Column(
                children: [
                  PassFieldWithLabel(
                    label: LocalKeys.currentPassword,
                    hintText: LocalKeys.enterYourCurrentPassword,
                    controller: cpm.currentPassController,
                    valueListenable: cpm.currentPassObs,
                    autofillHints: const [AutofillHints.password],
                  ),
                  PassFieldWithLabel(
                    label: LocalKeys.newPassword,
                    hintText: LocalKeys.enterNewPassword,
                    controller: cpm.newPassController,
                    valueListenable: cpm.newPassObs,
                    autofillHints: const [AutofillHints.newPassword],
                    validator: (pass) {
                      return pass.toString().validPass;
                    },
                  ),
                  PassFieldWithLabel(
                    label: LocalKeys.confirmPassword,
                    hintText: LocalKeys.reEnterNewPassword,
                    controller: TextEditingController(),
                    valueListenable: cpm.cNewPassObs,
                    validator: (v) {
                      if (v.toString() != cpm.newPassController.text) {
                        return LocalKeys.passwordDidNotMatch;
                      }
                      return null;
                    },
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ValueListenableBuilder(
          valueListenable: cpm.loading,
          builder: (context, loading, child) => CustomButton(
            onPressed: () {
              cpm.tryToChangePassword(context);
            },
            btText: LocalKeys.saveChanges,
            isLoading: loading,
          ),
        ),
      ),
    );
  }
}
