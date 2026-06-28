import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/booking_services/place_order_service.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';

class OrderSummaryCostInfo extends StatelessWidget {
  final PlaceOrderService po;
  const OrderSummaryCostInfo({super.key, required this.po});

  @override
  Widget build(BuildContext context) {
    if (po.orderResponseModel.orderDetails == null) {
      return const SizedBox();
    }
    final orderDetails = po.orderResponseModel.orderDetails!;
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          InfoTile(title: LocalKeys.subtotal, value: orderDetails.subTotal.cur),
          12.toHeight,
          InfoTile(title: LocalKeys.vat, value: orderDetails.tax.cur),
          12.toHeight,
          InfoTile(
              title: LocalKeys.discount, value: orderDetails.couponAmount.cur),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(
            title: LocalKeys.total,
            value: orderDetails.total.cur,
            fontSize: 18,
          ),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(
            title: LocalKeys.paymentGateway,
            value: orderDetails.paymentGateway
                    ?.replaceAll("_", " ")
                    .capitalize
                    .tr() ??
                "---",
          ),
          12.toHeight,
          InfoTile(
            title: LocalKeys.paymentStatus,
            value: orderDetails.paymentStatus?.getPaymentStatus ?? "---",
          ),
          12.toHeight,
          InfoTile(
            title: LocalKeys.orderStatus,
            value: orderDetails.paymentStatus?.getOrderStatus ?? "---",
          ),
          12.toHeight,
        ],
      ),
    );
  }
}
