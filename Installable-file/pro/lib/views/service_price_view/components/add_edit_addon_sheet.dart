import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/currency_icon.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/field_with_label.dart';

import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class AddEditAddonSheet extends StatelessWidget {
  final id;
  final TextEditingController titleCon;
  final TextEditingController priceCon;
  final TextEditingController descCon;
  const AddEditAddonSheet({
    super.key,
    this.id,
    required this.titleCon,
    required this.priceCon,
    required this.descCon,
  });

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: EdgeInsets.only(
        left: 24,
        right: 24,
        bottom: MediaQuery.of(context).viewInsets.bottom,
      ),
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
          Row(
            children: [
              Expanded(
                  flex: 7,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      FieldWithLabel(
                        label: LocalKeys.title,
                        hintText: LocalKeys.enterAddonTitle,
                        isRequired: true,
                        onChanged: (title) {
                          titleCon.text = title;
                        },
                      ),
                      8.toHeight,
                    ],
                  )),
              12.toWidth,
              Expanded(
                  flex: 3,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      FieldWithLabel(
                        label: LocalKeys.price,
                        hintText: 10.cur,
                        isRequired: true,
                        keyboardType: TextInputType.number,
                        onChanged: (title) {
                          priceCon.text = title;
                        },
                        prefixIcon: const Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            CurrencyIcon(
                              height: 32,
                            )
                          ],
                        ),
                      ),
                      8.toHeight,
                    ],
                  )),
            ],
          ),
          FieldWithLabel(
            label: LocalKeys.description,
            hintText: LocalKeys.enterDescription,
            onChanged: (title) {
              descCon.text = title;
            },
            minLines: 3,
          ),
          4.toHeight,
          CustomButton(
              onPressed: () {
                AddEditServiceViewModel.instance.addEditRemoveAddons(id,
                    title: titleCon.text,
                    price: priceCon.text,
                    desc: descCon.text);
                context.popTrue;
              },
              btText: LocalKeys.addAddon),
          32.toHeight,
        ],
      ),
    );
  }
}
