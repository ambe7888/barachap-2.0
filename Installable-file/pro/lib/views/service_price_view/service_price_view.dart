import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/currency_icon.dart';
import 'package:prohand/utils/components/field_with_label.dart';

import '../../helper/local_keys.g.dart';
import '../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'components/service_addons_add.dart';
import 'components/service_discount_field.dart';
import 'components/service_staffs_option.dart';

class ServicePriceView extends StatelessWidget {
  const ServicePriceView({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Divider(
            height: 8,
            thickness: 8,
            color: context.color.backgroundColor,
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.servicePrice,
                  style: context.titleLarge?.bold,
                ),
                Text(
                  LocalKeys.enterServicePrice,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.primaryContrastColor),
                ),
                24.toHeight,
                FieldWithLabel(
                  label: LocalKeys.price,
                  hintText: LocalKeys.enterBasicPrice,
                  controller: aem.priceController,
                  isRequired: true,
                  prefixIcon: const Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      CurrencyIcon(
                        height: 32,
                      ),
                    ],
                  ),
                ),
                const ServiceDiscountField(),
                24.toHeight,
                const ServiceAddonsAdd(),
                24.toHeight,
                const ServiceStaffsOption(),
              ],
            ),
          )
        ],
      ),
    );
  }
}
