import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:prohandy_client/services/profile_services/profile_info_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/view_models/submit_review_view_model/submit_review_view_model.dart';
import 'package:prohandy_client/views/submit_review_view/submit_review_view.dart';
import 'package:provider/provider.dart';

import '../../../services/dynamics/cancellation_policy_service.dart';
import '../../../view_models/order_details_view_model/order_details_view_model.dart';
import '../../cancel_order_view/cancel_order_view.dart';
import '../../request_refund_view/request_refund_view.dart';

class SuborderButtons extends StatelessWidget {
  final SubOrder subOrder;
  final OrderDetailsService orderDetailsService;

  const SuborderButtons({
    super.key,
    required this.subOrder,
    required this.orderDetailsService,
  });

  @override
  Widget build(BuildContext context) {
    final myId =
        Provider.of<ProfileInfoService>(
          context,
          listen: false,
        ).profileInfoModel.userDetails?.id;
    final reviews = subOrder.reviews;
    bool alreadyReviewed = false;

    // Check for review status
    for (Review review in reviews ?? []) {
      if (review.reviewerId?.toString() == myId.toString() &&
          review.subOrderId?.toString() == subOrder.id.toString()) {
        alreadyReviewed = true;
        break;
      }
    }

    // If status is not "2" (completed) and not already reviewed, mark as reviewed
    if (subOrder.status.toString() != "2" && !alreadyReviewed) {
      alreadyReviewed = true;
    }

    // Check if any button should be shown
    bool hasRefundButton =
        (["2"].contains(subOrder.status?.toString())) &&
        subOrder.refundDetails == null;

    bool hasCancelButton = false;
    final cs = CancellationPolicyService.instance;
    if (((cs.cancellationData.value["available_type"] == "certain_time" &&
                ((orderDetailsService
                                .orderDetailsModel
                                .orderDetails
                                ?.createdAt !=
                            null
                        ? DateTime.now()
                            .difference(
                              orderDetailsService
                                  .orderDetailsModel
                                  .orderDetails!
                                  .createdAt!,
                            )
                            .inMinutes
                        : 0) <=
                    cs.cancellationData.value["time_in_min"]
                        .toString()
                        .tryToParse)) ||
            cs.cancellationData.value["available_type"] == "always") &&
        (["0", "1"].contains(subOrder.status?.toString()))) {
      hasCancelButton = true;
    }

    bool hasCompletionButtons =
        subOrder.completionHistory?.lastOrNull?.status == "0";
    bool hasReviewButton =
        subOrder.status.toString() == "2" && !alreadyReviewed;

    debugPrint(
      "!hasRefundButton$hasRefundButton &&!hasCancelButton$hasCancelButton &&!hasCompletionButtons$hasCompletionButtons &&!hasReviewButton $hasReviewButton"
          .toString(),
    );
    // Check if any button should be shown before rendering the container
    if (!hasRefundButton &&
        !hasCancelButton &&
        !hasCompletionButtons &&
        !hasReviewButton) {
      return const SizedBox(); // Return empty SizedBox if no buttons should be shown
    }

    // If at least one button should be shown, proceed with rendering
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      width: double.infinity,
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        border: Border(
          top: BorderSide(color: context.color.primaryBorderColor),
        ),
      ),
      child: Wrap(
        runSpacing: 8,
        children: [
          if (hasRefundButton)
            Wrap(
              children: [
                SizedBox(
                  width: double.infinity,
                  child: OutlinedButton.icon(
                    onPressed: () async {
                      OrderDetailsViewModel.instance.selectedGateway.value =
                          null;
                      context.toPage(
                        RequestRefundView(subOrderId: subOrder.id),
                      );
                    },
                    icon: const Icon(Icons.money_off),
                    label: Text(LocalKeys.requestRefund),
                  ),
                ),
              ],
            ),
          if (hasCancelButton)
            SizedBox(
              width: double.infinity,
              child: CustomButton(
                onPressed: () async {
                  if (["complete", "1"].contains(
                    orderDetailsService
                        .orderDetailsModel
                        .orderDetails!
                        .paymentStatus,
                  )) {
                    OrderDetailsViewModel.instance.selectedGateway.value = null;
                    context.toPage(CancelOrderView(subOrderId: subOrder.id));
                    return;
                  }

                  Alerts().confirmationAlert(
                    context: context,
                    title: LocalKeys.areYouSure,
                    description: cs.cancellationData.value["description"],
                    onConfirm: () async {
                      final result = await Provider.of<OrderDetailsService>(
                        context,
                        listen: false,
                      ).tryCancelOrder(subOrderId: subOrder.id);
                      if (result) {
                        context.pop;
                      }
                    },
                  );
                },
                btText: LocalKeys.cancelOrder,
                backgroundColor: context.color.primaryWarningColor,
              ),
            ),
          if (hasCompletionButtons || hasReviewButton)
            Row(
              children: [
                if (hasReviewButton)
                  Expanded(
                    flex: 1,
                    child: ElevatedButton.icon(
                      onPressed: () => _handleSubmitReview(context),
                      icon: const Icon(Icons.star),
                      label: Text(LocalKeys.submitReview),
                    ),
                  ),
              ],
            ),
        ],
      ),
    );
  }

  // Handle the complete order action
  void _handleCompleteOrder(BuildContext context) {
    final status = subOrder.completionHistory?.lastOrNull?.status;
    if (status == "0") {
      Alerts().confirmationAlert(
        context: context,
        title: LocalKeys.areYouSure,
        buttonText: LocalKeys.complete,
        buttonColor: primaryColor,
        onConfirm: () async {
          final result = await orderDetailsService.tryCompleteOrder(
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

  // Handle the submit review action
  void _handleSubmitReview(BuildContext context) {
    SubmitReviewViewModel.instance.suborderNotifier.value = subOrder;
    context.toPage(SubmitReviewView(suborder: subOrder));
  }

  // Get the text for the primary button based on completion status
  String _getPrimaryButtonText(String? completeRequestStatus) {
    switch (completeRequestStatus) {
      case "0":
        return LocalKeys.completeOrder;
      case "2":
        return LocalKeys.submitReview;
      default:
        return LocalKeys.na;
    }
  }
}
