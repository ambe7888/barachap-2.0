import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../models/staff_models/staff_list_model.dart';
import '../../../services/order_services/todays_order_service.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../order_details_view/order_details_view.dart';

class StaffAssignedOrderSheet extends StatelessWidget {
  final Staff staff;
  const StaffAssignedOrderSheet({super.key, required this.staff});

  @override
  Widget build(BuildContext context) {
    return Consumer<TodaysOrdersService>(builder: (context, ts, child) {
      return SingleChildScrollView(
        padding: EdgeInsets.only(
          left: 24,
          right: 24,
          bottom: MediaQuery.of(context).viewInsets.bottom,
        ),
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
            Row(
              children: [
                CustomNetworkImage(
                  height: 48,
                  width: 48,
                  radius: 24,
                  imageUrl: staff.image,
                  fit: BoxFit.cover,
                  name: staff.fullname,
                  userPreloader: true,
                ),
                12.toWidth,
                Expanded(
                  flex: 1,
                  child: Text(
                    staff.fullname ?? "",
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: context.titleMedium?.bold,
                  ),
                )
              ],
            ),
            12.toHeight.divider,
            12.toHeight,
            Text(
              LocalKeys.assignedToday,
              style: context.titleMedium,
            ),
            8.toHeight,
            Wrap(
              runSpacing: 12,
              children: ts.myOrdersModel.allOrders
                  .where((order) =>
                      order.staff?.id?.toString() == staff.id.toString())
                  .map((order) {
                return GestureDetector(
                  onTap: () {
                    context.toPage(OrderDetailsView(
                      orderId: order.id,
                    ));
                  },
                  child: SquircleContainer(
                      radius: 8,
                      padding: 12.paddingAll,
                      borderColor: context.color.primaryBorderColor,
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          CustomNetworkImage(
                            height: 44,
                            width: 44,
                            radius: 6,
                            imageUrl: order.serviceImage,
                            fit: BoxFit.cover,
                          ),
                          12.toWidth,
                          Expanded(
                            flex: 1,
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  order.subOrderLocations?.address ??
                                      LocalKeys.na,
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: context.titleSmall?.bold,
                                ),
                                if (order.schedule != null) ...[
                                  6.toHeight,
                                  SquircleContainer(
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 8, vertical: 4),
                                      borderColor:
                                          context.color.primaryBorderColor,
                                      radius: 4,
                                      child: Text(
                                        order.schedule!,
                                        style: context.bodySmall?.bold.copyWith(
                                            color: context
                                                .color.secondaryContrastColor),
                                      ))
                                ]
                              ],
                            ),
                          )
                        ],
                      )),
                );
              }).toList(),
            ),
            20.toHeight,
          ],
        ),
      );
    });
  }
}
