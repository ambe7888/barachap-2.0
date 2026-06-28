import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

class ServiceCardPrice extends StatelessWidget {
  final num price;
  final num discountPrice;
  const ServiceCardPrice(
      {super.key, required this.price, required this.discountPrice});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Text(
          "${discountPrice > 0 ? discountPrice.cur : price.cur} ",
          style: context.titleSmall
              ?.copyWith(color: primaryColor, fontSize: 14)
              .bold6,
        ),
        if (discountPrice > 0)
          Text(
            price.cur,
            style: context.bodySmall
                ?.copyWith(
                  color: context.color.tertiaryContrastColo,
                  decoration: TextDecoration.lineThrough,
                  decorationColor: context.color.tertiaryContrastColo,
                )
                .bold5,
          ),
      ],
    );
  }
}
