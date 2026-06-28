import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';

class BookingSummeryAddons extends StatelessWidget {
  const BookingSummeryAddons({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return svm.addons.value.values.isEmpty
        ? const SizedBox()
        : Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                LocalKeys.addons,
                style: context.titleSmall?.bold5.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
              4.toHeight,
              Wrap(
                runSpacing: 4,
                children: svm.addons.value.values.map((addon) {
                  final title = addon["title"];
                  final qty = addon["quantity"];
                  final price = addon["price"];
                  return AddonsInfos(title: title, qty: qty, price: price);
                }).toList(),
              ),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
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
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: context.bodySmall,
          ),
        ),
        Expanded(
          flex: 1,
          child: Text(
            "x$qty",
            style: context.bodySmall,
          ),
        ),
        Expanded(
          flex: 1,
          child: Text(
            (price * qty).cur,
            textAlign: TextAlign.end,
            style: context.bodySmall,
          ),
        ),
      ],
    );
  }
}
