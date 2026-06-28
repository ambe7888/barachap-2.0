import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/order_services/submit_review_service.dart';
import 'package:prohand/utils/components/alerts.dart';

import '../../models/order_models/order_details_model.dart';

class SubmitReviewViewModel {
  final TextEditingController commentController = TextEditingController();
  final ValueNotifier<double> ratingCountNotifier = ValueNotifier(5);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<OrderDetails?> orderNotifier = ValueNotifier(null);

  final GlobalKey<FormState> formKey = GlobalKey();

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
    if (formKey.currentState?.validate() != true) return;
    Alerts().confirmationAlert(
        context: context,
        title: LocalKeys.areYouSure,
        buttonColor: primaryColor,
        buttonText: LocalKeys.submit,
        onConfirm: () async {
          await SubmitReviewService()
              .trySubmittingReview(
            orderId: orderNotifier.value?.orderId,
            suborderId: orderNotifier.value?.id,
            clientId: orderNotifier.value?.clientId,
            rating: ratingCountNotifier.value,
            message: commentController.text,
            serviceId: orderNotifier.value?.service?.id,
          )
              .then((v) {
            if (v != true) return;
            context.pop;
            context.pop;
          });
        });
  }
}
