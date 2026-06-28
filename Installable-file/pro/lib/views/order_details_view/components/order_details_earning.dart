import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/order_models/order_details_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import 'order_earnings_breakdown_sheet.dart';

class OrderDetailsEarning extends StatelessWidget {
  final OrderDetails orderDetails;
  const OrderDetailsEarning({
    super.key,
    required this.orderDetails,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            LocalKeys.youWillReceive,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.bodySmall
                ?.copyWith(color: context.color.tertiaryContrastColo),
          ),
          GestureDetector(
            onTap: () {
              showModalBottomSheet(
                context: context,
                isScrollControlled: true,
                builder: (context) {
                  return OrderEarningsBreakdownSheet(
                      orderDetails: orderDetails);
                },
              );
            },
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  (orderDetails.total -
                          orderDetails.commissionAmount -
                          orderDetails.tax)
                      .cur,
                  style: context.titleSmall?.bold,
                ),
                6.toHeight,
                SquircleContainer(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  borderColor: context.color.primaryBorderColor,
                  radius: 4,
                  child: Row(
                    children: [
                      Text(
                        LocalKeys.breakdown,
                        style: context.bodySmall?.bold,
                      ),
                      6.toWidth,
                      Transform.rotate(
                        angle: context.dProvider.textDirectionRight ? pi : 0,
                        child: SvgAssets.chevron.toSVGSized(
                          16,
                          color: context.color.secondaryContrastColor,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
