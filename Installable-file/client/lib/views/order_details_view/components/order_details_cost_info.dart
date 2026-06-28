import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';
import 'package:prohandy_client/views/order_details_view/components/pay_again_button.dart';

class OrderDetailsCostInfo extends StatelessWidget {
  final OrderDetailsService od;
  const OrderDetailsCostInfo({super.key, required this.od});

  @override
  Widget build(BuildContext context) {
    final orderDetails = od.orderDetailsModel.orderDetails!;
    return Column(
      children: [
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.accentContrastColor,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(
                  title: LocalKeys.subtotal, value: orderDetails.subTotal.cur),
              12.toHeight,
              InfoTile(title: LocalKeys.vat, value: orderDetails.tax.cur),
              12.toHeight,
              InfoTile(
                  title: LocalKeys.discount,
                  value: "- ${orderDetails.couponAmount.cur}"),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              InfoTile(
                title: LocalKeys.total,
                value: orderDetails.total.cur,
                fontSize: 18,
              ),
            ],
          ),
        ),
        8.toHeight,
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.accentContrastColor,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(
                title: LocalKeys.paymentGateway,
                value: orderDetails.paymentGateway
                        ?.replaceAll("_", " ")
                        .capitalize ??
                    "---",
              ),
              12.toHeight,
              InfoTile(
                title: LocalKeys.paymentStatus,
                value: orderDetails.paymentStatus.toString().getPaymentStatus,
              ),
              12.toHeight,
              InfoTile(
                title: LocalKeys.orderStatus,
                value: orderDetails.status.toString().getOrderStatus,
              ),
              12.toHeight,
              if (!(["cash_on_delivery", "manual_payment", "cod"]
                      .contains(orderDetails.paymentGateway)) &&
                  orderDetails.paymentStatus == "pending")
                const PayAgainButton(),
            ],
          ),
        ),
      ],
    );
  }
}
