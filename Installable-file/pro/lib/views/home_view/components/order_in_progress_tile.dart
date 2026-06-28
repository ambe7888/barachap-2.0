import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../models/order_models/order_list_model.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../order_details_view/order_details_view.dart';
import '../../order_list_view/components/order_payment_status_chip.dart';
import '../../order_list_view/components/order_status_chip.dart';

class OrderInProgressTile extends StatelessWidget {
  final Order order;
  const OrderInProgressTile({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(OrderDetailsView(
          orderId: order.id,
        ));
      },
      child: SquircleContainer(
          width: context.width - 72,
          padding: 12.paddingAll,
          radius: 10,
          borderColor: context.color.primaryBorderColor,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CustomNetworkImage(
                height: 72,
                width: 72,
                radius: 10,
                fit: BoxFit.cover,
                imageUrl: order.serviceImage,
              ),
              12.toWidth,
              Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Wrap(
                      spacing: 6,
                      runSpacing: 6,
                      children: [
                        OrderStatusChip(status: order.status.toString()),
                        OrderPaymentStatusChip(
                            status: order.paymentStatus.toString(),
                            isCOD: ["cash_on_delivery", "cod"]
                                .contains(order.paymentGateway))
                      ],
                    ),
                    6.toHeight,
                    Text(
                      order.subOrderLocations?.address ?? LocalKeys.na,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: context.titleMedium?.bold,
                    ),
                    if (order.schedule != null) ...[
                      6.toHeight,
                      SquircleContainer(
                        borderColor: context.color.primaryBorderColor,
                        radius: 4,
                        padding: const EdgeInsets.symmetric(
                            horizontal: 6, vertical: 4),
                        child: Text(order.schedule!,
                            style: context.bodySmall?.bold),
                      )
                    ],
                  ],
                ),
              )
            ],
          )),
    );
  }
}
