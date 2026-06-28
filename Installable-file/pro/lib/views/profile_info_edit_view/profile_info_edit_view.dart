import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/date_picker_field.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/warning_widget.dart';
import 'package:prohand/views/profile_info_edit_view/components/store_images.dart';

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
      body: SingleChildScrollView(
          child: Container(
        width: double.infinity,
        margin: const EdgeInsets.only(top: 8, bottom: 2),
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.cardFillColor,
        child: Form(
          key: pem.basicFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              WarningWidget(
                text: LocalKeys.profileInfoShouldMatchID,
              ),
              12.toHeight,
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
              DatePickerField(
                dateNotifier: pem.dob,
                fieldLabel: LocalKeys.dateOfBirth,
                isRequired: true,
                firstDate: DateTime(1960),
              ),
              FieldWithLabel(
                label: LocalKeys.videoUrl,
                controller: pem.videoUrlController,
                hintText: LocalKeys.enterYoutubeVideoUrl,
              ),
              FieldWithLabel(
                label: LocalKeys.about,
                controller: pem.aboutController,
                hintText: LocalKeys.enterSomethingAboutYou,
                minLines: 3,
                maxLines: 3,
              ),
              StoreImages(),
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
                  if (pem.dob.value == null) {
                    LocalKeys.selectDob.showToast();
                    return;
                  }
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
