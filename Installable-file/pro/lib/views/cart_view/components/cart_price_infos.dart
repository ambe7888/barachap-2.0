import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../utils/components/info_tile.dart';
import '../../booking_payment_choose_view/booking_payment_choose_view.dart';

class CartPriceInfos extends StatelessWidget {
  const CartPriceInfos({super.key});

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
              InfoTile(title: LocalKeys.subtotal, value: 146.99.cur),
              Row(
                children: [
                  12.toHeight,
                ],
              ),
              InfoTile(title: LocalKeys.vat, value: "(+9%) ${13.cur}"),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              InfoTile(title: LocalKeys.total, value: 155.37.cur),
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
                context.toPage(const BookingPaymentChooseView());
              },
              btText: LocalKeys.continueO),
        ),
      ],
    );
  }
}
