import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../helper/local_keys.g.dart';

class OrderRefundRequests extends StatelessWidget {
  final SubOrder subOrder;
  const OrderRefundRequests({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return Consumer<OrderDetailsService>(
      builder: (context, od, child) {
        if (subOrder.refundDetails == null) {
          return const SizedBox();
        }
        return Container(
          width: double.infinity,
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          margin: const EdgeInsets.only(top: 8),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              RichText(
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                text: TextSpan(
                  text: "${LocalKeys.refundRequest} . ",
                  style: context.bodySmall?.copyWith(
                    color: context.color.tertiaryContrastColo,
                  ),
                  children: [
                    TextSpan(
                      text:
                          (subOrder.refundDetails?.status.toString() ?? "")
                              .getRefundStatus,
                      style: context.bodySmall?.copyWith(
                        color:
                            (subOrder.refundDetails?.status.toString() ?? "")
                                .getRefundPrimaryStatusColor,
                      ),
                    ),
                  ],
                ),
              ),
              6.toHeight,
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    subOrder.refundDetails?.cancelReason ?? "",
                    style: context.titleSmall,
                  ),
                  4.toHeight,
                  Text(
                    timeago.format(
                      subOrder.refundDetails?.createdAt ?? DateTime.now(),
                      locale: context.dProvider.languageSlug,
                    ),
                    style: context.bodySmall,
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
