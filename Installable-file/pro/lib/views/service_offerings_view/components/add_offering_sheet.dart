import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_with_label.dart';

import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class AddOfferingSheet extends StatelessWidget {
  final id;
  final TextEditingController title;
  final TextEditingController description;
  const AddOfferingSheet({
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
        key: aes.includesFormKey,
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
              label: LocalKeys.title,
              hintText: LocalKeys.enterTitle,
              isRequired: true,
              controller: title,
              validator: (value) {
                if (value.toString().trim().length < 10) {
                  return LocalKeys.titleMustContainMoreCharacter;
                }
                return null;
              },
            ),
            FieldWithLabel(
              label: LocalKeys.description,
              hintText: LocalKeys.enterDescription,
              isRequired: true,
              minLines: 3,
              controller: description,
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
                      if (aes.includesFormKey.currentState?.validate() !=
                          true) {
                        return;
                      }
                      AddEditServiceViewModel.instance.addEditRemoveIncludes(
                        id,
                        title: title.text,
                        description: description.text,
                      );
                      context.popTrue;
                    },
                    child: Text(id != null
                        ? LocalKeys.saveChanges
                        : LocalKeys.addOffer),
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
