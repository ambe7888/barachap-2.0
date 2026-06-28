import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/empty_spacer_helper.dart';

class ExpansionTitle extends StatelessWidget {
  const ExpansionTitle({
    super.key,
    required this.priceAmount,
    required this.feeAmount,
    required this.paymentGateway,
    required this.paymentStatus,
    required this.id,
    required this.controller,
    required this.paymentFailed,
  });

  final num priceAmount;
  final num feeAmount;
  final String paymentGateway;
  final String paymentStatus;
  final id;
  final ExpansionTileController? controller;
  final bool paymentFailed;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "#$id",
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
          style: context.titleSmall
              ?.copyWith(fontWeight: FontWeight.w600, color: primaryColor),
        ),
        4.toHeight,
        Wrap(
          spacing: 6,
          crossAxisAlignment: WrapCrossAlignment.center,
          children: [
            Text(
              priceAmount.toStringAsFixed(2).cur,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: context.titleMedium?.copyWith(
                  fontWeight: FontWeight.w600,
                  color: context.color.primaryContrastColor),
            ),
            if (feeAmount > 0)
              SquircleContainer(
                radius: 8,
                borderColor: color.primaryBorderColor,
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                child: Text(
                  "${LocalKeys.fee}: ${feeAmount.toStringAsFixed(2).cur}",
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleMedium?.copyWith(
                      fontWeight: FontWeight.w600,
                      color: context.color.primaryContrastColor),
                ),
              ),
          ],
        ),
        EmptySpaceHelper.emptyHeight(4),
        Text(
          paymentGateway,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
          style: context.titleMedium
              ?.copyWith(fontSize: 14, color: primaryColor)
              .bold6,
        ),
        EmptySpaceHelper.emptyHeight(4),
        Row(
          children: [
            RichText(
              text: TextSpan(
                  text: "${LocalKeys.status}: ",
                  style: context.titleSmall?.copyWith(
                      fontSize: 14, color: context.color.primaryContrastColor),
                  children: [
                    TextSpan(
                      text: paymentStatus.withdrawPaymentStatus,
                      style: context.titleSmall?.copyWith(
                          color: paymentStatus
                              .getWithdrawHistoryPrimaryStatusColor),
                    )
                  ]),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            )
          ],
        ),
        EmptySpaceHelper.emptyHeight(4),
      ],
    );
  }
}
