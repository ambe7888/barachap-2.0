import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/order_services/order_details_service.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../helper/local_keys.g.dart';
import '../../../models/order_models/order_details_model.dart';

class OrderRefundRequest extends StatelessWidget {
  final RefundDetails? refundDetails;
  const OrderRefundRequest({super.key, required this.refundDetails});

  @override
  Widget build(BuildContext context) {
    return Consumer<OrderDetailsService>(
      builder: (context, od, child) {
        if (refundDetails == null) {
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
                      text: (refundDetails?.status.toString() ?? "")
                          .getRefundStatus,
                      style: context.bodySmall?.copyWith(
                        color: (refundDetails?.status.toString() ?? "")
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
                    refundDetails?.cancelReason ?? "",
                    style: context.titleSmall,
                  ),
                  4.toHeight,
                  Text(
                    timeago.format(
                      refundDetails?.createdAt ?? DateTime.now(),
                      locale: context.dProvider.languageSlug,
                    ),
                    style: context.bodySmall,
                  ),
                  if (refundDetails?.status == "0") ...[
                    4.toHeight,
                    Row(
                      children: [
                        Expanded(
                          flex: 1,
                          child: OutlinedButton.icon(
                            onPressed: () async {
                              Alerts().confirmationAlert(
                                  context: context,
                                  title: LocalKeys.declineRequest,
                                  buttonColor:
                                      context.color.primaryWarningColor,
                                  buttonText: LocalKeys.decline,
                                  onConfirm: () async {
                                    await od.tryDeclineRefund(
                                      id: refundDetails?.id,
                                    );
                                  });
                            },
                            icon: const Icon(Icons.close),
                            label: Text(LocalKeys.decline),
                          ),
                        ),
                        12.toWidth,
                        Expanded(
                          flex: 1,
                          child: ElevatedButton.icon(
                            onPressed: () async {
                              Alerts().confirmationAlert(
                                  context: context,
                                  title: LocalKeys.acceptRequest,
                                  buttonText: LocalKeys.accept,
                                  onConfirm: () async {
                                    await od.tryDeclineRefund(
                                      id: refundDetails?.id,
                                    );
                                  });
                            },
                            icon: const Icon(Icons.check),
                            label: Text(LocalKeys.accept),
                          ),
                        ),
                      ],
                    )
                  ],
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
