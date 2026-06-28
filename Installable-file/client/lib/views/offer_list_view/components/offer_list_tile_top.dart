import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:timeago/timeago.dart' as timeAgo;

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class OfferListTileTop extends StatelessWidget {
  final num budget;
  final DateTime createdAt;
  final dynamic status;

  const OfferListTileTop(
      {super.key, required this.budget, required this.createdAt, this.status});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
            flex: 1,
            child: Wrap(
              spacing: 8,
              children: [
                Text(
                  budget.cur,
                  style: context.titleLarge?.copyWith(color: primaryColor).bold,
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
    );
  }
}
