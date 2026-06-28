import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class SummeryDateSchedule extends StatelessWidget {
  final DateTime? date;
  final String schedule;
  const SummeryDateSchedule(
      {super.key, required this.date, required this.schedule});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.when,
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
                      ? "---"
                      : DateFormat(
                              "EEE, dd MMMM", context.dProvider.languageSlug)
                          .format(date!),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleSmall?.bold,
                ),
                SquircleContainer(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    borderColor: context.color.primaryBorderColor,
                    radius: 4,
                    child: Text(
                      schedule,
                      style: context.bodySmall?.copyWith(
                          color: context.color.secondaryContrastColor),
                    ))
              ])
        ],
      ),
    );
  }
}
