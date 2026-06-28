import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class OrderDateSchedule extends StatelessWidget {
  final DateTime? date;
  final String? schedule;
  const OrderDateSchedule(
      {super.key, required this.date, required this.schedule});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.schedule,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.bodySmall
                ?.copyWith(color: context.color.tertiaryContrastColo),
          ),
          6.toHeight,
          Wrap(
              spacing: 8,
              crossAxisAlignment: WrapCrossAlignment.center,
              children: [
                Text(
                  date == null
                      ? LocalKeys.na
                      : DateFormat(
                              "EEE, dd MMMM", context.dProvider.languageSlug)
                          .format(date!),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleSmall?.bold,
                ),
                if (schedule != null)
                  SquircleContainer(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 8, vertical: 4),
                      borderColor: context.color.primaryBorderColor,
                      radius: 4,
                      child: Text(
                        schedule!,
                        style: context.bodySmall?.bold.copyWith(
                            color: context.color.primaryContrastColor),
                      ))
              ]),
        ],
      ),
    );
  }
}
