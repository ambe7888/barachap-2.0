import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_button.dart';
import '../../utils/components/custom_dropdown.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import './../../helper/local_keys.g.dart';
import './../../services/profile_services/delete_account_service.dart';
import './../../utils/components/field_label.dart';
import './../../utils/components/field_with_label.dart';
import './../../utils/components/navigation_pop_icon.dart';
import './../../utils/components/pass_field_with_label.dart';
import './../../view_models/delete_account_view_model/delete_account_view_model.dart';

class DeleteAccountView extends StatelessWidget {
  const DeleteAccountView({super.key});

  @override
  Widget build(BuildContext context) {
    final dam = DeleteAccountViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.deleteAccount),
      ),
      body: SafeArea(
        child: Container(
          padding: 24.paddingAll,
          margin: const EdgeInsets.only(top: 16),
          height: double.infinity,
          color: context.color.accentContrastColor,
          child: Form(
            key: dam.formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FieldLabel(
                  label: LocalKeys.reason,
                  isRequired: true,
                ),
                Consumer<DeleteAccountService>(builder: (context, da, child) {
                  return CustomFutureWidget(
                    function: da.shouldAutoFetch ? da.fetchDepartments() : null,
                    shimmer: const CustomPreloader(),
                    child: ValueListenableBuilder(
                      valueListenable: dam.selectedReason,
                      builder: (context, value, child) {
                        return CustomDropdown(
                          LocalKeys.selectAReason,
                          (da.reasonsListModel.reasons
                              .map((e) => e.title ?? "")
                              .toList()),
                          (value) {
                            dam.selectedReason.value =
                                da.reasonsListModel.reasons.firstWhere(
                                    (element) => element.title == value);
                          },
                          value: value?.title,
                        );
                      },
                    ),
                  );
                }),
                16.toHeight,
                PassFieldWithLabel(
                  label: LocalKeys.currentPassword,
                  hintText: LocalKeys.enterYourCurrentPassword,
                  controller: dam.currentPassController,
                  valueListenable: dam.currentPassObs,
                  isRequired: true,
                ),
                FieldWithLabel(
                  label: LocalKeys.description,
                  hintText: LocalKeys.enterDescription,
                  controller: dam.descriptionController,
                  minLines: 5,
                  isRequired: true,
                  validator: (value) {
                    if ((value ?? "").trim().isEmpty) {
                      return LocalKeys.enterDescription;
                    }
                    return null;
                  },
                ),
              ],
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
        child: CustomButton(
          onPressed: () {
            dam.tryDeletingAccount(context);
          },
          btText: LocalKeys.delete,
          backgroundColor: context.color.primaryWarningColor,
        ),
      ),
    );
  }
}
