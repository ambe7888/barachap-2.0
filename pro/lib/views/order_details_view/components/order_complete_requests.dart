import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/order_services/order_details_service.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../helper/local_keys.g.dart';

class OrderCompleteRequests extends StatelessWidget {
  const OrderCompleteRequests({super.key});

  @override
  Widget build(BuildContext context) {
    final orderDetails =
        Provider.of<OrderDetailsService>(context, listen: false)
            .orderDetailsModel
            .orderDetails;
    if (orderDetails == null) {
      return const SizedBox();
    }
    return Consumer<OrderDetailsService>(builder: (context, od, child) {
      if ((od.orderDetailsModel.orderDetails?.completionHistory ?? [])
          .isEmpty) {
        return const SizedBox();
      }
      return Container(
        width: double.infinity,
        color: context.color.accentContrastColor,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        margin: const EdgeInsets.only(bottom: 8),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              LocalKeys.requestHistory,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: context.bodySmall
                  ?.copyWith(color: context.color.tertiaryContrastColo),
            ),
            6.toHeight,
            Wrap(
              children: od
                  .orderDetailsModel.orderDetails!.completionHistory!.reversed
                  .map((h) {
                return SquircleContainer(
                  child: Column(
                    children: [
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Column(
                            children: [
                              4.toHeight,
                              CircleAvatar(
                                radius: 6,
                                backgroundColor:
                                    h.status.getCompleteRequestStatusColor,
                              ),
                              4.toHeight,
                              Container(
                                height: 48,
                                width: 2,
                                color: h.status.getCompleteRequestStatusColor,
                              ),
                            ],
                          ),
                          12.toWidth,
                          Expanded(
                            flex: 1,
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  h.status.getCompleteRequestText,
                                  style: context.titleSmall?.bold,
                                ),
                                Text(
                                  h.status == "0"
                                      ? LocalKeys.requestInPending
                                      : (h.message ?? ""),
                                  style: context.titleSmall,
                                ),
                                4.toHeight,
                                Text(
                                  timeago.format(h.createdAt ?? DateTime.now(),
                                      locale: context.dProvider.languageSlug),
                                  style: context.bodySmall,
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                );
              }).toList(),
            ),
          ],
        ),
      );
    });
  }
}
