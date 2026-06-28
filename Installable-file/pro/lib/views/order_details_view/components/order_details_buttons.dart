import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/models/order_models/order_details_model.dart';
import 'package:prohand/views/accept_order_view/accept_order_view.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/order_services/order_details_service.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../../utils/components/alerts.dart';
import '../../../view_models/submit_review_view_model/submit_review_view_model.dart';
import '../../submit_review_view/submit_review_view.dart';

class OrderDetailsButtons extends StatelessWidget {
  final OrderDetails orderDetails;
  const OrderDetailsButtons({super.key, required this.orderDetails});

  @override
  Widget build(BuildContext context) {
    final od = Provider.of<OrderDetailsService>(context, listen: false);

    final myId = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails
        ?.id;
    final reviews = od.orderDetailsModel.orderDetails!.reviews;
    bool alreadyReviewed = false;
    for (Review re in reviews!) {
      if (re.reviewerId?.toString() == myId.toString()) {
        alreadyReviewed = true;
        break;
      }
    }
    return orderDetails.status == "5" ||
            alreadyReviewed ||
            orderDetails.status == "4"
        ? const SizedBox()
        : Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            decoration: BoxDecoration(
                color: context.color.accentContrastColor,
                border: Border(
                    top: BorderSide(color: context.color.primaryBorderColor))),
            child: Row(
              children: [
                if (["0", "1"].contains(orderDetails.status)) ...[
                  Expanded(
                    flex: 1,
                    child: OutlinedButton.icon(
                      onPressed: () {
                        switch (orderDetails.status) {
                          case "0":
                            Alerts().confirmationAlert(
                                context: context,
                                title: LocalKeys.areYouSure,
                                buttonText: LocalKeys.decline,
                                onConfirm: () async {
                                  final result = await od.tryDeclineOrder(
                                      orderId: orderDetails.orderId,
                                      id: orderDetails.id);
                                  if (result != true) return;
                                  context.pop;
                                });

                            break;
                          case "1":
                            Alerts().confirmationAlert(
                                context: context,
                                title: LocalKeys.areYouSure,
                                buttonText: LocalKeys.confirm,
                                onConfirm: () async {
                                  final result = await od.tryCancelOrder(
                                      orderId: orderDetails.orderId,
                                      id: orderDetails.id);
                                  if (result != true) return;
                                  context.pop;
                                });

                            break;
                          default:
                            debugPrint("no action needed".toString());
                        }
                      },
                      label: Text(outlinedButtonText),
                    ),
                  ),
                  12.toWidth
                ],
                Expanded(
                  flex: 1,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      if (orderDetails.status == "0") {
                        context.toPage(const AcceptOrderView());
                        return;
                      } else if (orderDetails.status == "1") {
                        Alerts().confirmationAlert(
                            context: context,
                            title: LocalKeys.areYouSure,
                            buttonText: LocalKeys.submit,
                            buttonColor: primaryColor,
                            onConfirm: () async {
                              final result =
                                  await od.sendOrderCompletionRequest(
                                      orderId: orderDetails.orderId,
                                      id: orderDetails.id);
                              context.pop;
                            });
                      } else if (!alreadyReviewed &&
                          orderDetails.status == "2") {
                        SubmitReviewViewModel.dispose;
                        SubmitReviewViewModel.instance.orderNotifier.value =
                            od.orderDetailsModel.orderDetails;
                        context.toPage(const SubmitReviewView());
                      }
                    },
                    label: Text(
                      primaryButtonText,
                      textAlign: TextAlign.center,
                    ),
                  ),
                ),
              ],
            ),
          );
  }

  String get primaryButtonText {
    switch (orderDetails.status) {
      case "0":
        return LocalKeys.acceptOrder;
      case "1":
        return LocalKeys.submitCompletionRequest;
      case "2":
        return LocalKeys.submitReview;
      case "3":
        return LocalKeys.submitCompletionRequest;
      default:
        return LocalKeys.na;
    }
  }

  String get outlinedButtonText {
    switch (orderDetails.status) {
      case "0":
        return LocalKeys.decline;
      case "1":
        return LocalKeys.cancelOrder;
      default:
        return LocalKeys.na;
    }
  }
}
