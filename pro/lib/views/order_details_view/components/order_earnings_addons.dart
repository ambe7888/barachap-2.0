import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/order_models/order_details_model.dart';
import 'package:prohand/utils/components/field_label.dart';

class OrderEarningsAddons extends StatelessWidget {
  final List<SubOrderAddon> addons;
  const OrderEarningsAddons({super.key, required this.addons});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FieldLabel(label: LocalKeys.addons),
        4.toHeight,
        if (addons.isEmpty)
          Text(
            LocalKeys.na,
            style: context.titleSmall,
          ),
        Wrap(
          runSpacing: 4,
          children: addons.map((addon) {
            return AddonsInfos(
                title: addon.title ?? LocalKeys.addons,
                qty: addon.quantity,
                price: (addon.price * addon.quantity));
          }).toList(),
        )
      ],
    );
  }
}

class AddonsInfos extends StatelessWidget {
  const AddonsInfos({
    super.key,
    required this.title,
    required this.qty,
    required this.price,
  });

  final String title;
  final num qty;
  final num price;

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          flex: 3,
          child: Text(
            title,
            style: context.titleSmall,
          ),
        ),
        Expanded(
          flex: 1,
          child: Text(
            "x$qty",
            style: context.titleSmall?.bold,
          ),
        ),
        Expanded(
          flex: 1,
          child: Text(
            price.cur,
            textAlign: TextAlign.end,
            style: context.titleSmall?.bold,
          ),
        ),
      ],
    );
  }
}
