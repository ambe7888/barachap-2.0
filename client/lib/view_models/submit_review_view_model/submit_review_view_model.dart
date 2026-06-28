import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/services/order_services/submit_review_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';

class SubmitReviewViewModel {
  final TextEditingController commentController = TextEditingController();
  final ValueNotifier<double> ratingCountNotifier = ValueNotifier(5);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<SubOrder?> suborderNotifier = ValueNotifier(null);

  SubmitReviewViewModel._init();
  static SubmitReviewViewModel? _instance;
  static SubmitReviewViewModel get instance {
    _instance ??= SubmitReviewViewModel._init();
    return _instance!;
  }

  SubmitReviewViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void trySubmittingReview(BuildContext context) async {
    Alerts().confirmationAlert(
        context: context,
        title: LocalKeys.areYouSure,
        buttonColor: primaryColor,
        buttonText: LocalKeys.submit,
        onConfirm: () async {
          await SubmitReviewService()
              .trySubmittingReview(
            orderId: suborderNotifier.value?.orderId,
            suborderId: suborderNotifier.value?.id,
            providerId: suborderNotifier.value?.providerId,
            rating: ratingCountNotifier.value,
            message: commentController.text,
            serviceId: suborderNotifier.value?.service?.id,
            adminId: suborderNotifier.value?.adminId,
          )
              .then((v) {
            if (v != true) return;
            context.pop;
            context.pop;
          });
        });
  }
}
