import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

import '/helper/extension/context_extension.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/currency_icon.dart';
import '../../../utils/components/field_with_label.dart';

class ServiceDiscountField extends StatelessWidget {
  const ServiceDiscountField({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: aem.addDiscount,
      builder: (context, value, child) {
        return Column(
          children: [
            GestureDetector(
              onTap: () {
                aem.addDiscount.value = !value;
              },
              child: Row(
                children: [
                  Checkbox(
                    value: value,
                    onChanged: (v) {
                      aem.addDiscount.value = !value;
                    },
                  ),
                  4.toWidth,
                  Expanded(
                    flex: 8,
                    child: Text(
                      LocalKeys.addDiscountedPrice,
                      style: context.titleSmall,
                    ),
                  ),
                ],
              ),
            ),
            if (value) ...[
              12.toHeight,
              FieldWithLabel(
                label: LocalKeys.discount,
                hintText: LocalKeys.enterDiscountedPrice,
                controller: aem.discountPriceController,
                prefixIcon: const Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CurrencyIcon(
                      height: 32,
                    ),
                  ],
                ),
              )
            ],
          ],
        );
      },
    );
  }
}
