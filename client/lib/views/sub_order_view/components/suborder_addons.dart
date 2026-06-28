import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/service/service_details_model.dart';
import 'package:prohandy_client/utils/components/field_label.dart';

class SuborderAddons extends StatelessWidget {
  final List<Addon> addons;
  const SuborderAddons({super.key, required this.addons});

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
            style: context.titleSmall?.bold,
          ),
        Wrap(
          runSpacing: 4,
          children: addons.map((addon) {
            return AddonsInfos(
                title: addon.title ?? LocalKeys.na,
                qty: addon.quantity.toInt(),
                price: addon.price);
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
  final int qty;
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
