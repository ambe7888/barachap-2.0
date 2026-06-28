import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../components/currency_icon.dart';
import '../../components/marquee.dart';

class ServiceCardPrice extends StatelessWidget {
  final num price;
  final num discountPrice;
  final num views;
  final num ordersCount;
  const ServiceCardPrice(
      {super.key,
      required this.price,
      required this.discountPrice,
      required this.views,
      required this.ordersCount});

  @override
  Widget build(BuildContext context) {
    return Marquee(
        child: Wrap(
      children: [
        subInfo(context, const CurrencyIcon(), LocalKeys.price,
            (discountPrice > 0 ? discountPrice : price).cur),
        subInfo(
          context,
          SvgAssets.invisible
              .toSVGSized(24, color: context.color.tertiaryContrastColo),
          LocalKeys.view,
          "$views",
          isMiddle: true,
        ),
        subInfo(
            context,
            SvgAssets.list
                .toSVGSized(24, color: context.color.tertiaryContrastColo),
            LocalKeys.orders,
            "$ordersCount"),
      ],
    ));
  }

  Widget subInfo(BuildContext context, Widget icon, String title, String value,
      {bool isMiddle = false}) {
    return Container(
      alignment: Alignment.center,
      padding: 6.paddingH,
      decoration: BoxDecoration(
          border: !isMiddle
              ? null
              : Border(
                  left: BorderSide(
                      color: context.color.primaryBorderColor, width: 2),
                  right: BorderSide(
                      color: context.color.primaryBorderColor, width: 2),
                )),
      constraints: BoxConstraints(
        minWidth: (context.width - 52) / 3,
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          icon,
          4.toWidth,
          RichText(
            text: TextSpan(text: title, style: context.bodySmall, children: [
              TextSpan(
                  text: " $value",
                  style: context.bodySmall?.bold.copyWith(
                    color: primaryColor,
                  ))
            ]),
          )
        ],
      ),
    );
  }
}
