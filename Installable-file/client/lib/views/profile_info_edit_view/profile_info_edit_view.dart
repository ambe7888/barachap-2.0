import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';

import '../../view_models/profile_edit_view_model/profile_edit_view_model.dart';

class ProfileInfoEditView extends StatelessWidget {
  const ProfileInfoEditView({super.key});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.personalInformation),
      ),
      body: SafeArea(
          child: Container(
        width: double.infinity,
        margin: const EdgeInsets.only(top: 8, bottom: 2),
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Form(
          key: pem.basicFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              FieldWithLabel(
                label: LocalKeys.firstName,
                hintText: LocalKeys.enterFirstName,
                controller: pem.fNameController,
                isRequired: true,
                validator: (value) {
                  if ((value ?? "").isEmpty) {
                    return LocalKeys.enterAValidName;
                  }
                  return null;
                },
              ),
              FieldWithLabel(
                label: LocalKeys.lastName,
                hintText: LocalKeys.enterLastName,
                controller: pem.lNameController,
                isRequired: true,
                validator: (value) {
                  if ((value ?? "").isEmpty) {
                    return LocalKeys.enterAValidName;
                  }
                  return null;
                },
              ),
            ],
          ),
        ),
      )),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ValueListenableBuilder(
            valueListenable: pem.isLoading,
            builder: (context, value, child) {
              return CustomButton(
                onPressed: () {
                  if (!(pem.basicFormKey.currentState!.validate())) return;

                  pem.updateBasicInfo(context);
                },
                btText: LocalKeys.saveChanges,
                isLoading: value,
              );
            }),
      ),
    );
  }
}
