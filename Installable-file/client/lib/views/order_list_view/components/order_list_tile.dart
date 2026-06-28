import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/order_models/order_list_model.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';

import '../../order_details_view/order_details_view.dart';
import 'order_payment_status_chip.dart';

class OrderListTile extends StatelessWidget {
  final Order order;
  const OrderListTile({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(OrderDetailsView(orderId: order.id));
      },
      child: SquircleContainer(
        margin: const EdgeInsets.symmetric(horizontal: 24),
        padding: const EdgeInsets.all(16),
        radius: 10,
        borderColor: context.color.primaryBorderColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Wrap(
              spacing: 6,
              crossAxisAlignment: WrapCrossAlignment.center,
              children: [
                Text(
                  "#${order.id}",
                  style: context.bodySmall?.bold.copyWith(color: primaryColor),
                ),
                SquircleContainer(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 8,
                    vertical: 4,
                  ),
                  color: (order.status).toString().getOrderMutedStatusColor,
                  radius: 4,
                  child: Text(
                    (order.status).toString().getOrderStatus,
                    style: context.bodySmall?.copyWith(
                      color:
                          (order.status).toString().getOrderPrimaryStatusColor,
                    ),
                  ),
                ),
              ],
            ),
            4.toHeight,
            Text(
              order.createdAt == null
                  ? "---"
                  : DateFormat(
                    "EEE, dd MMM yyyy",
                    dProvider.languageSlug,
                  ).format(order.createdAt ?? DateTime.now()),
              style: context.titleSmall?.bold,
            ),
            8.toHeight,
            Wrap(
              spacing: 4,
              crossAxisAlignment: WrapCrossAlignment.center,
              children: [
                Text(
                  order.total.cur,
                  style: context.titleSmall?.bold.copyWith(color: primaryColor),
                ),
                OrderPaymentStatusChip(
                  status: order.paymentStatus.toString(),
                  isCOD: [
                    "cash_on_delivery",
                    "cod",
                  ].contains(order.paymentGateway),
                ),
                SquircleContainer(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 8,
                    vertical: 4,
                  ),
                  borderColor: context.color.primaryBorderColor,
                  radius: 4,
                  child: Text(
                    (order.type).toString().capitalize,
                    style: context.bodySmall,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
