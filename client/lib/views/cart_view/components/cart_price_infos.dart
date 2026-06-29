import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/services/service/cart_service.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../utils/components/info_tile.dart';
import '../../booking_payment_choose_view/booking_payment_choose_view.dart';

class CartPriceInfos extends StatelessWidget {
  final CartService cs;
  const CartPriceInfos({super.key, required this.cs});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      children: [
        Container(
          padding: 24.paddingH,
          color: context.color.accentContrastColor,
          child: Wrap(
            children: [
              Row(
                children: [
                  16.toHeight,
                ],
              ),
              InfoTile(
                  title: LocalKeys.total,
                  value: cs.subTotal.cur),
              Row(
                children: [
                  16.toHeight,
                ],
              ),
            ],
          ),
        ),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
              color: context.color.accentContrastColor,
              border: Border(
                  top: BorderSide(color: context.color.primaryBorderColor))),
          child: CustomButton(
              onPressed: () {
                ServiceBookingViewModel.dispose;
                context.toPage(const BookingPaymentChooseView());
              },
              btText: LocalKeys.continueO),
        ),
      ],
    );
  }
}
