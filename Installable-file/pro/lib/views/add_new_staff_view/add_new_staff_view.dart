import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/image_assets.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/utils/components/custom_network_image.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';

import '../../view_models/staff_view_model/staff_view_model.dart';
import 'components/add_staff_button.dart';

class AddNewStaffView extends StatelessWidget {
  final bool editing;
  const AddNewStaffView({super.key, this.editing = false});

  @override
  Widget build(BuildContext context) {
    final sm = StaffViewModel.instance;

    return Scaffold(
      backgroundColor: context.color.cardFillColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(editing ? LocalKeys.editStaff : LocalKeys.newStaff),
      ),
      body: SingleChildScrollView(
        child: Form(
          key: sm.formKey,
          child: Column(
            children: [
              Divider(
                height: 8,
                thickness: 8,
                color: context.color.backgroundColor,
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Stack(
                      children: [
                        116.toWidth,
                        ValueListenableBuilder(
                          valueListenable: sm.selectedImage,
                          builder: (context, image, child) => GestureDetector(
                            onTap: () {
                              sm.selectImage();
                            },
                            child: image == null
                                ? SizedBox(
                                    height: 120,
                                    width: 120,
                                    child: ImageAssets.avatar
                                        .toAImage(fit: BoxFit.cover),
                                  )
                                : CustomNetworkImage(
                                    height: 120,
                                    width: 120,
                                    radius: 60,
                                    userPreloader: true,
                                    imageUrl: sm.imageUrl,
                                    filePath: image.path,
                                    errorWidget: SizedBox(
                                      height: 120,
                                      width: 120,
                                      child: CircleAvatar(
                                        radius: 30,
                                        child: ImageAssets.avatar
                                            .toAImage(fit: BoxFit.cover),
                                      ),
                                    ),
                                    fit: BoxFit.cover,
                                  ),
                          ),
                        ),
                        Positioned(
                          bottom: 0,
                          right: 0,
                          child: GestureDetector(
                            onTap: () {
                              sm.selectImage();
                            },
                            child: CircleAvatar(
                              radius: 18,
                              backgroundColor: primaryColor,
                              child: SvgAssets.gallery.toSVGSized(
                                16,
                                color: context.color.accentContrastColor,
                              ),
                            ),
                          ),
                        )
                      ],
                    ),
                    16.toHeight,
                    FieldWithLabel(
                      label: LocalKeys.firstName,
                      hintText: LocalKeys.enterFirstName,
                      controller: sm.firstNameController,
                      keyboardType: TextInputType.name,
                      isRequired: true,
                      validator: (value) {
                        if ((value ?? "").trim().isEmpty) {
                          return LocalKeys.enterAValidName;
                        }
                        return null;
                      },
                    ),
                    FieldWithLabel(
                      label: LocalKeys.lastName,
                      hintText: LocalKeys.enterLastName,
                      controller: sm.lastNameController,
                      keyboardType: TextInputType.name,
                      isRequired: true,
                      validator: (value) {
                        if ((value ?? "").trim().isEmpty) {
                          return LocalKeys.enterAValidName;
                        }
                        return null;
                      },
                    ),
                    FieldWithLabel(
                      label: LocalKeys.email,
                      hintText: LocalKeys.enterEmail,
                      keyboardType: TextInputType.emailAddress,
                      controller: sm.emailController,
                      validator: (value) {
                        if ((value ?? "").trim().isEmpty) {
                          return null;
                        }
                        return value.toString().validateEmail
                            ? null
                            : LocalKeys.enterValidEmailAddress;
                      },
                    ),
                    FieldWithLabel(
                      label: LocalKeys.phone,
                      hintText: LocalKeys.phoneHintWithCode,
                      keyboardType: TextInputType.number,
                      controller: sm.phoneController,
                      validator: (value) {
                        if ((value ?? "").isEmpty) {
                          return null;
                        }
                        if (value.toString().validatePhone) {
                          return null;
                        }
                        return LocalKeys.enterAValidPhoneNumber;
                      },
                    ),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
      bottomNavigationBar: const AddStaffButton(),
    );
  }
}
