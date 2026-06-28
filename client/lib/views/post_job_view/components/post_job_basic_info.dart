import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/category_dropdown.dart';
import 'package:prohandy_client/utils/components/date_picker_field.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/time_picker_field.dart';

import '../../../utils/components/field_label.dart';
import '../../../view_models/post_job_view_model/post_job_view_model.dart';
import 'post_job_address.dart';

class PostJobBasicInfo extends StatelessWidget {
  const PostJobBasicInfo({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    return SingleChildScrollView(
      child: Container(
        margin: const EdgeInsets.all(16),
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: context.color.primaryBorderColor.withOpacity(0.6),
            width: 1,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Form(
          key: pjm.basicFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                LocalKeys.postAJob,
                style: context.titleLarge?.bold,
              ),
              6.toHeight,
              Text(
                LocalKeys.jobBasicInfoSuggestion,
                style: context.titleSmall,
              ),
              24.toHeight,
              FieldWithLabel(
                label: LocalKeys.jobTitle,
                hintText: LocalKeys.enterJobTitle,
                controller: pjm.titleController,
                isRequired: true,
                validator: (value) {
                  if (value.toString().trim().length < 10) {
                    return LocalKeys.titleMustContainMoreCharacter;
                  }
                  return null;
                },
              ),
              CategoryDropdown(
                isRequired: true,
                categoryNotifier: pjm.selectedCategory,
              ),
              FieldLabel(
                label: LocalKeys.address,
                isRequired: true,
              ),
              const PostJobAddress(),
              DatePickerField(
                dateNotifier: pjm.selectedDate,
                isRequired: true,
              ),
              TimePickerField(
                timeNotifier: pjm.selectedTime,
                isRequired: true,
              )
            ],
          ),
        ),
      ),
    );
  }
}
