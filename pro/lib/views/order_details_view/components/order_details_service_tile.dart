import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/order_models/order_details_model.dart';

import '../../../utils/components/custom_network_image.dart';
import '../../order_list_view/components/order_payment_status_chip.dart';
import '../../order_list_view/components/order_status_chip.dart';

class OrderDetailsServiceTile extends StatelessWidget {
  final OrderDetails orderDetails;
  const OrderDetailsServiceTile({super.key, required this.orderDetails});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {},
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomNetworkImage(
              height: 72,
              width: 72,
              radius: 10,
              fit: BoxFit.cover,
              imageUrl: orderDetails.service?.image,
            ),
            12.toWidth,
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    orderDetails.service?.title ?? LocalKeys.na,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: context.titleMedium?.bold,
                  ),
                  6.toHeight,
                  Wrap(
                    spacing: 6,
                    runSpacing: 6,
                    children: [
                      OrderStatusChip(status: orderDetails.status.toString()),
                      OrderPaymentStatusChip(
                          status: orderDetails.paymentStatus.toString(),
                          isCOD: ["cash_on_delivery", "cod"]
                              .contains(orderDetails.paymentGateway)),
                    ],
                  ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
