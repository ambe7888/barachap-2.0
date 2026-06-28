import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/models/order_models/order_list_model.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../utils/components/custom_network_image.dart';
import '../../order_details_view/order_details_view.dart';
import 'order_list_tile_schedule.dart';
import 'order_payment_status_chip.dart';
import 'order_status_chip.dart';

class OrderListTile extends StatelessWidget {
  final Order order;
  const OrderListTile({
    super.key,
    required this.order,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(OrderDetailsView(
          orderId: order.id,
        ));
      },
      child: SquircleContainer(
        margin: const EdgeInsets.symmetric(horizontal: 24),
        padding: const EdgeInsets.all(16),
        radius: 10,
        borderColor: context.color.primaryBorderColor,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (order.jobPostId == null) ...[
              CustomNetworkImage(
                height: 72,
                width: 72,
                radius: 10,
                fit: BoxFit.cover,
                imageUrl: order.jobPostId != null ? null : order.serviceImage,
              ),
              12.toWidth
            ],
            if (order.jobPostId != null) ...[
              SquircleContainer(
                height: 72,
                width: 72,
                radius: 10,
                color: context.color.mutedContrastColor,
                child: Center(
                  child: SvgAssets.jobs.toSVGSized(32,
                      color: context.color.secondaryContrastColor),
                ),
              ),
              12.toWidth
            ],
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Wrap(
                    spacing: 6,
                    runSpacing: 6,
                    crossAxisAlignment: WrapCrossAlignment.center,
                    children: [
                      Text(
                        "#${order.id}",
                        style: context.titleSmall?.bold
                            .copyWith(color: primaryColor),
                      ),
                      OrderStatusChip(status: order.status.toString()),
                      OrderPaymentStatusChip(
                          status: order.paymentStatus.toString(),
                          isCOD: ["cash_on_delivery", "cod"]
                              .contains(order.paymentGateway)),
                    ],
                  ),
                  6.toHeight,
                  Text(
                    order.subOrderLocations?.address ?? LocalKeys.na,
                    style: context.titleSmall?.bold,
                  ),
                  2.toHeight,
                  OrderListTileSchedule(
                    date: order.date,
                    schedule: order.schedule,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
