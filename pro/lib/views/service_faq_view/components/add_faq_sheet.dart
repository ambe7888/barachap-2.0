import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class AddFaqSheet extends StatelessWidget {
  final id;
  final TextEditingController title;
  final TextEditingController description;
  const AddFaqSheet({
    super.key,
    this.id,
    required this.title,
    required this.description,
  });

  @override
  Widget build(BuildContext context) {
    final aes = AddEditServiceViewModel.instance;
    return SingleChildScrollView(
      padding: EdgeInsets.only(
        left: 24,
        right: 24,
        bottom: MediaQuery.of(context).viewInsets.bottom,
      ),
      child: Form(
        key: aes.faqFormKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Align(
              alignment: Alignment.center,
              child: Container(
                height: 4,
                width: 48,
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12),
                  color: context.color.mutedContrastColor,
                ),
              ),
            ),
            24.toHeight,
            FieldWithLabel(
              label: LocalKeys.question,
              hintText: LocalKeys.enterAQuestion,
              isRequired: true,
              controller: title,
              validator: (value) {
                if (value.toString().trim().length < 10) {
                  return LocalKeys.enterAValidQuestion;
                }
                return null;
              },
            ),
            FieldWithLabel(
              label: LocalKeys.answer,
              hintText: LocalKeys.enterTheAnswer,
              isRequired: true,
              minLines: 3,
              controller: description,
              validator: (value) {
                if (value.toString().trim().length < 2) {
                  return LocalKeys.enterAValidAnswer;
                }
                return null;
              },
            ),
            4.toHeight,
            Row(
              children: [
                Expanded(
                  flex: 1,
                  child: OutlinedButton(
                    onPressed: () {
                      context.popFalse;
                    },
                    child: Text(LocalKeys.cancel),
                  ),
                ),
                12.toWidth,
                Expanded(
                  flex: 1,
                  child: ElevatedButton(
                    onPressed: () {
                      if (aes.faqFormKey.currentState?.validate() != true) {
                        return;
                      }
                      AddEditServiceViewModel.instance.addEditRemoveFAQ(
                        id,
                        title: title.text,
                        description: description.text,
                      );

                      context.popTrue;
                    },
                    child: Text(
                        id != null ? LocalKeys.saveChanges : LocalKeys.addFAQ),
                  ),
                ),
              ],
            ),
            32.toHeight,
          ],
        ),
      ),
    );
  }
}
