import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/identity_verify_view_model/identity_verify_view_model.dart';

import 'components/id_type_buttons.dart';
import 'components/identity_verify_form_button.dart';
import 'components/identity_verify_images.dart';

class IdentityVerificationFormView extends StatelessWidget {
  const IdentityVerificationFormView({super.key});

  @override
  Widget build(BuildContext context) {
    final ivm = IVViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.cardFillColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.verifyIdentity),
      ),
      body: SingleChildScrollView(
        child: Form(
          key: ivm.formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Divider(
                height: 8,
                thickness: 8,
                color: context.color.backgroundColor,
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 12, horizontal: 24),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      LocalKeys.verifyIdentity,
                      style: context.headlineLarge?.bold,
                    ),
                    4.toHeight,
                    Text(
                      LocalKeys.chooseTypeOfYourIdCard,
                      style: context.bodySmall
                          ?.copyWith(color: context.color.tertiaryContrastColo),
                    ),
                    24.toHeight,
                    const IDTypeButtons(),
                    32.toHeight,
                    FieldWithLabel(
                      label: LocalKeys.country,
                      hintText: LocalKeys.enterCountry,
                      isRequired: true,
                      controller: ivm.countryController,
                      validator: (value) {
                        if ((value ?? "").isEmpty) {
                          return LocalKeys.enterCountry;
                        }
                        return null;
                      },
                    ),
                    Row(
                      children: [
                        Expanded(
                            flex: 1,
                            child: FieldWithLabel(
                              label: LocalKeys.state,
                              hintText: LocalKeys.enterState,
                              controller: ivm.stateController,
                              isRequired: true,
                              validator: (value) {
                                if ((value ?? "").isEmpty) {
                                  return LocalKeys.enterState;
                                }
                                return null;
                              },
                            )),
                        12.toWidth,
                        Expanded(
                          flex: 1,
                          child: FieldWithLabel(
                            label: LocalKeys.city,
                            hintText: LocalKeys.enterCity,
                            controller: ivm.cityController,
                            isRequired: true,
                            validator: (value) {
                              if ((value ?? "").isEmpty) {
                                return LocalKeys.enterCity;
                              }
                              return null;
                            },
                          ),
                        ),
                      ],
                    ),
                    FieldWithLabel(
                      label: LocalKeys.zipCode,
                      hintText: LocalKeys.enterZipCode,
                      controller: ivm.zipController,
                      isRequired: true,
                      validator: (value) {
                        if ((value ?? "").isEmpty) {
                          return LocalKeys.enterZipCode;
                        }
                        return null;
                      },
                    ),
                    FieldWithLabel(
                      label: LocalKeys.idNumber,
                      hintText: LocalKeys.enterIdNumber,
                      controller: ivm.idController,
                      isRequired: true,
                      validator: (value) {
                        if ((value ?? "").isEmpty) {
                          return LocalKeys.enterIdNumber;
                        }
                        return null;
                      },
                    ),
                    FieldWithLabel(
                      label: LocalKeys.address,
                      hintText: LocalKeys.enterAddress,
                      controller: ivm.addressController,
                      isRequired: true,
                      validator: (value) {
                        if ((value ?? "").isEmpty) {
                          return LocalKeys.enterAddress;
                        }
                        return null;
                      },
                    ),
                    16.toHeight,
                    const IdentityVerifyImages(),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
      bottomNavigationBar: const IdentityVerifyFormButton(),
    );
  }
}
