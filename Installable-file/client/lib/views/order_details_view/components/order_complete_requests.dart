import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/alerts.dart';
import '../../decline_completion_request_view/decline_completion_request_view.dart';

class OrderCompleteRequests extends StatelessWidget {
  final SubOrder subOrder;
  const OrderCompleteRequests({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return Consumer<OrderDetailsService>(
      builder: (context, od, child) {
        if ((subOrder.completionHistory ?? []).isEmpty) {
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
              Text(
                LocalKeys.requestHistory,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: context.bodySmall?.copyWith(
                  color: context.color.tertiaryContrastColo,
                ),
              ),
              6.toHeight,
              Wrap(
                children:
                    subOrder.completionHistory!.reversed.map((h) {
                      return Column(
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
                                    height: 64,
                                    width: 2,
                                    color:
                                        h.status.getCompleteRequestStatusColor,
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
                                      timeago.format(
                                        h.createdAt ?? DateTime.now(),
                                        locale: context.dProvider.languageSlug,
                                      ),
                                      style: context.bodySmall,
                                    ),

                                    if (h.status == "0")
                                      Row(
                                        children: [
                                          Expanded(
                                            flex: 1,
                                            child: OutlinedButton.icon(
                                              onPressed: () {
                                                context.toPage(
                                                  DeclineCompletionRequestView(
                                                    subOrder: subOrder,
                                                  ),
                                                );
                                              },
                                              icon: const Icon(Icons.close),
                                              label: Text(LocalKeys.decline),
                                            ),
                                          ),
                                          12.toWidth,
                                          Expanded(
                                            flex: 1,
                                            child: ElevatedButton.icon(
                                              onPressed:
                                                  () => _handleCompleteOrder(
                                                    context,
                                                  ),
                                              icon: const Icon(Icons.check),
                                              label: Text(LocalKeys.accept),
                                            ),
                                          ),
                                        ],
                                      ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ],
                      );
                    }).toList(),
              ),
            ],
          ),
        );
      },
    );
  }

  void _handleCompleteOrder(BuildContext context) {
    final status = subOrder.completionHistory?.lastOrNull?.status;
    if (status == "0") {
      Alerts().confirmationAlert(
        context: context,
        title: LocalKeys.areYouSure,
        buttonText: LocalKeys.complete,
        buttonColor: primaryColor,
        onConfirm: () async {
          final result = await Provider.of<OrderDetailsService>(
            context,
            listen: false,
          ).tryCompleteOrder(
            orderId: subOrder.orderId,
            subOrderId: subOrder.id,
          );

          if (result == true) {
            context.pop;
          }
        },
      );
    }
  }
}
