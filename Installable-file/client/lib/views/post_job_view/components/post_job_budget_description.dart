import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';

import '../../../helper/local_keys.g.dart';
import 'post_job_gallery.dart';

class PostJobBudgetDescription extends StatelessWidget {
  const PostJobBudgetDescription({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    return SingleChildScrollView(
      child: Container(
        margin: const EdgeInsets.only(top: 16),
        padding: 24.paddingAll,
        color: context.color.accentContrastColor,
        child: Form(
          key: pjm.budgetFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                LocalKeys.jobDescription,
                style: context.titleLarge?.bold,
              ),
              6.toHeight,
              Text(
                LocalKeys.jobDescriptionSectionSuggestion,
                style: context.titleSmall,
              ),
              24.toHeight,
              FieldWithLabel(
                label: LocalKeys.budget,
                hintText: LocalKeys.enterYourBudget,
                controller: pjm.budgetController,
                keyboardType: TextInputType.number,
                isRequired: true,
                prefixIcon: SizedBox(
                  width: 24,
                  child: Center(
                    child: Text(
                      context.dProvider.currencySymbol,
                      style: context.titleLarge?.bold6.copyWith(
                        color: context.color.secondaryContrastColor,
                      ),
                    ),
                  ),
                ),
                validator: (value) {
                  if (value.toString().tryToParse <= 0) {
                    return LocalKeys.enterValidAmount;
                  }
                  return null;
                },
              ),
              FieldWithLabel(
                label: LocalKeys.description,
                hintText: LocalKeys.enterDescription,
                textInputAction: TextInputAction.newline,
                controller: pjm.descriptionController,
                isRequired: true,
                minLines: 3,
                maxLines: 100,
                validator: (value) => value.toString().trim().length < 20
                    ? LocalKeys.descriptionLengthValidation
                    : null,
              ),
              12.toHeight,
              const PostJobGallery(),
            ],
          ),
        ),
      ),
    );
  }
}
