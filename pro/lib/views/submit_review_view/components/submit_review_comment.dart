import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_with_label.dart';

import '../../../view_models/submit_review_view_model/submit_review_view_model.dart';

class SubmitReviewComment extends StatelessWidget {
  const SubmitReviewComment({super.key});

  @override
  Widget build(BuildContext context) {
    final srm = SubmitReviewViewModel.instance;
    return Form(
      key: srm.formKey,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            FieldWithLabel(
              label: LocalKeys.comment,
              hintText: LocalKeys.describeYourExperience,
              minLines: 3,
              controller: srm.commentController,
              validator: (value) {
                if ((value ?? "").isEmpty) {
                  return LocalKeys.enterAComment;
                }
                return null;
              },
            )
          ],
        ),
      ),
    );
  }
}
