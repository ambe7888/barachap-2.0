import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/views/job_details_view/job_details_view.dart';

import '../../../models/job_models/job_list_model.dart';

class OrderDetailsJobTile extends StatelessWidget {
  final Job job;
  final bool hideBorder;
  const OrderDetailsJobTile(
      {super.key, required this.job, this.hideBorder = false});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toNamed(JobDetailsView.routeName, arguments: job.id);
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              job.title ?? "---",
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: context.titleMedium?.bold,
            ),
            6.toHeight,
            Row(
              children: [
                Expanded(
                  flex: 1,
                  child: Text(
                    job.budget.cur,
                    style: context.titleSmall?.bold.copyWith(
                      color: primaryColor,
                    ),
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }
}
