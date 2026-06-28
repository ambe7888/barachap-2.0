import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../view_models/landding_view_model/landding_view_model.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';
import '../../booking_payment_choose_view/booking_payment_choose_view.dart';

class BookingSummeryButtons extends StatelessWidget {
  const BookingSummeryButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        border: Border(
          top: BorderSide(color: context.color.primaryBorderColor),
        ),
      ),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: OutlinedButton(
              onPressed: () {
                svm.tryAddingCart(context);
                LandingViewModel.instance.navigateToLanding(context);
              },
              child: Text(LocalKeys.addToCart),
            ),
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: ElevatedButton(
              onPressed: () {
                svm.setInstantBooking();
                context.toPage(const BookingPaymentChooseView());
              },
              child: Text(LocalKeys.proceedToPay),
            ),
          ),
        ],
      ),
    );
  }
}
