import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class OrderListTileSchedule extends StatelessWidget {
  final DateTime? date;
  final String? schedule;
  const OrderListTileSchedule(
      {super.key, required this.date, required this.schedule});

  @override
  Widget build(BuildContext context) {
    return Wrap(
        spacing: 8,
        runSpacing: 4,
        crossAxisAlignment: WrapCrossAlignment.center,
        children: [
          Text(
            date == null
                ? LocalKeys.na
                : DateFormat("EEE, dd MMMM", context.dProvider.languageSlug)
                    .format(date!),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
            style: context.titleSmall?.bold
                .copyWith(color: context.color.tertiaryContrastColo),
          ),
          if (schedule != null)
            SquircleContainer(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                borderColor: context.color.primaryBorderColor,
                radius: 4,
                child: Text(
                  schedule!,
                  style: context.bodySmall?.bold
                      .copyWith(color: context.color.secondaryContrastColor),
                ))
        ]);
  }
}
