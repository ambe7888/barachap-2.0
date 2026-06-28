import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/views/job_details_view/job_details_view.dart';
import 'package:prohandy_client/views/job_list_view/components/job_tile_id_status.dart';

import '../../../models/job/job_list_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobTile extends StatelessWidget {
  final Job job;
  const JobTile({super.key, required this.job});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toNamed(JobDetailsView.routeName, arguments: job.id);
      },
      child: Container(
        margin: 25.paddingH,
        padding: 16.paddingAll,
        color: Colors.transparent,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            JobTileIdStatus(
              jobId: job.id,
              jobStatus: job.status,
            ),
            6.toHeight,
            Text(
              job.title ?? "---",
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: context.titleMedium?.bold,
            ),
            6.toHeight,
            Wrap(
              spacing: 8,
              crossAxisAlignment: WrapCrossAlignment.center,
              children: [
                RichText(
                  text: TextSpan(
                      text: "${LocalKeys.budget}: ",
                      style: context.titleSmall?.bold.copyWith(
                        color: context.color.tertiaryContrastColo,
                      ),
                      children: [
                        TextSpan(
                          text: job.budget.cur,
                          style: context.titleSmall?.bold.copyWith(
                            color: primaryColor,
                          ),
                        )
                      ]),
                ),
                if (job.jobOffersCount > 0)
                  SquircleContainer(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 8, vertical: 4),
                      borderColor: context.color.primaryBorderColor,
                      radius: 14,
                      child: Text(
                        "${job.jobOffersCount} ${LocalKeys.offers}",
                        style: context.bodySmall?.copyWith(
                            color: context.color.secondaryContrastColor),
                      ))
              ],
            )
          ],
        ),
      ),
    );
  }
}
