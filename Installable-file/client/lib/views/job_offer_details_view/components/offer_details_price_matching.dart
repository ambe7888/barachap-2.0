import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:timeago/timeago.dart' as timeAgo;

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class OfferDetailsPriceMatching extends StatelessWidget {
  final num budget;
  final String? matchings;
  final DateTime createdAt;
  final dynamic status;
  const OfferDetailsPriceMatching(
      {super.key,
      required this.budget,
      this.matchings,
      this.status,
      required this.createdAt});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        children: [
          Expanded(
              flex: 1,
              child: Wrap(
                crossAxisAlignment: WrapCrossAlignment.center,
                spacing: 8,
                children: [
                  Text(
                    budget.cur,
                    style: context.titleLarge
                        ?.copyWith(color: primaryColor, fontSize: 36)
                        .bold,
                  ),
                  if (status?.toString() == "2")
                    SquircleContainer(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 6, vertical: 2),
                        borderColor: context.color.primaryWarningColor,
                        radius: 6,
                        child: Text(
                          LocalKeys.rejected,
                          style: context.bodySmall?.copyWith(
                              color: context.color.primaryWarningColor),
                        )),
                ],
              )),
          Text(
            timeAgo.format(createdAt),
            style: context.bodyMedium,
          )
        ],
      ),
    );
  }
}
