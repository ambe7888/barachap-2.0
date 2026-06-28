import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/views/service_price_view/components/service_staff_select.dart';

import '../../../helper/local_keys.g.dart';
import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class ServiceStaffsOption extends StatelessWidget {
  const ServiceStaffsOption({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return ValueListenableBuilder(
        valueListenable: aem.selectStaffOption,
        builder: (context, value, child) {
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              GestureDetector(
                onTap: () {
                  aem.selectStaffOption.value = !value;
                },
                child: Row(
                  children: [
                    Checkbox(
                      value: value,
                      onChanged: (v) {
                        aem.selectStaffOption.value = !value;
                      },
                    ),
                    4.toWidth,
                    Expanded(
                      flex: 8,
                      child: Text(
                        LocalKeys.clientCanChoseStaff,
                        style: context.titleSmall,
                      ),
                    ),
                  ],
                ),
              ),
              if (value)
                ValueListenableBuilder(
                    valueListenable: aem.anyStaffOption,
                    builder: (context, rValue, child) {
                      return Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          GestureDetector(
                            onTap: () {
                              aem.anyStaffOption.value = !rValue;
                            },
                            child: Row(
                              children: [
                                12.toWidth,
                                Radio(
                                  value: rValue,
                                  groupValue: value,
                                  onChanged: (v) {
                                    aem.anyStaffOption.value = !rValue;
                                  },
                                ),
                                4.toWidth,
                                Expanded(
                                  flex: 8,
                                  child: Text(
                                    LocalKeys.clientCanChooseAnyStaff,
                                    style: context.titleSmall,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          GestureDetector(
                            onTap: () {
                              aem.anyStaffOption.value = !rValue;
                            },
                            child: Row(
                              children: [
                                12.toWidth,
                                Radio(
                                  value: !rValue,
                                  groupValue: value,
                                  onChanged: (v) {
                                    aem.anyStaffOption.value = !rValue;
                                  },
                                ),
                                4.toWidth,
                                Expanded(
                                  flex: 8,
                                  child: Text(
                                    LocalKeys.clientCanChooseAllocatedStaffOnly,
                                    style: context.titleSmall,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          ValueListenableBuilder(
                              valueListenable: aem.anyStaffOption,
                              builder: (context, rValue, child) {
                                return Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: rValue || !value
                                      ? []
                                      : [
                                          16.toHeight,
                                          FieldLabel(
                                              label: LocalKeys
                                                  .chooseStaffToAllocate),
                                          8.toHeight,
                                          ServiceStaffSelect(),
                                        ],
                                );
                              })
                        ],
                      );
                    }),
            ],
          );
        });
  }
}
