import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/order_models/order_details_model.dart';
import 'package:prohand/utils/components/info_tile.dart';

import '../../../utils/components/custom_squircle_widget.dart';
import '../../../view_models/offer_details_view_model/offer_details_view_model.dart';
import 'order_earnings_addons.dart';

class OrderEarningsBreakdownSheet extends StatelessWidget {
  final OrderDetails orderDetails;
  const OrderEarningsBreakdownSheet({super.key, required this.orderDetails});

  @override
  Widget build(BuildContext context) {
    final adm = OfferDetailsViewModel.instance;
    final discount =
        orderDetails.subTotal + orderDetails.tax - orderDetails.total;
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
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
          InfoTile(
              title: LocalKeys.package, value: orderDetails.basicPrice.cur),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          OrderEarningsAddons(addons: orderDetails.subOrderAddons),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(title: LocalKeys.subtotal, value: orderDetails.subTotal.cur),
          12.toHeight,
          InfoTile(
              title: LocalKeys.discount,
              value: "- ${discount < 0 ? 0 : (discount).cur}"),
          12.toHeight,
          InfoTile(
              title: LocalKeys.platformFee,
              value: "- ${orderDetails.commissionAmount.cur}"),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          Row(
            children: [
              Expanded(
                  flex: 1,
                  child: Text(
                    LocalKeys.youWillReceive,
                    style: context.bodySmall?.bold5.copyWith(
                      color: context.color.secondaryContrastColor,
                    ),
                  )),
              12.toWidth,
              if (orderDetails.paymentGateway != null) ...[
                SquircleContainer(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    borderColor: context.color.primaryBorderColor,
                    radius: 4,
                    child: Text(
                      orderDetails.paymentGateway!,
                      style: context.bodySmall
                          ?.copyWith(color: context.color.tertiaryContrastColo),
                    ))
              ],
              12.toWidth,
              ConstrainedBox(
                  constraints:
                      BoxConstraints(maxWidth: (context.width - 52) / 2),
                  child: Text(
                    (orderDetails.total -
                            orderDetails.commissionAmount -
                            orderDetails.tax)
                        .cur,
                    style: context.titleSmall?.bold.copyWith(),
                  )),
            ],
          ),
          12.toHeight,
        ],
      ),
    );
  }
}
