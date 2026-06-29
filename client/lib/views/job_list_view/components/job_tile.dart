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
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: context.color.primaryBorderColor.withOpacity(0.6),
            width: 1,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            JobTileIdStatus(
              jobId: job.id,
              jobStatus: job.status,
            ),
            8.toHeight,
            Text(
              job.title ?? "---",
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: context.titleMedium?.bold,
            ),
            12.toHeight,
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    RichText(
                      text: TextSpan(
                          text: "${LocalKeys.budget}: ",
                          style: context.bodyMedium?.copyWith(
                            color: context.color.tertiaryContrastColo,
                          ),
                          children: [
                            TextSpan(
                              text: job.budget.cur,
                              style: context.titleMedium?.bold.copyWith(
                                color: primaryColor,
                              ),
                            )
                          ]),
                    ),
                    12.toWidth,
                    Icon(
                      Icons.remove_red_eye_outlined,
                      size: 16,
                      color: context.color.secondaryContrastColor,
                    ),
                    4.toWidth,
                    Text(
                      "${job.view ?? 0}",
                      style: context.bodySmall?.copyWith(
                        color: context.color.secondaryContrastColor,
                      ),
                    ),
                  ],
                ),
                if (job.jobOffersCount > 0)
                  SquircleContainer(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 5),
                      borderColor: context.color.primaryBorderColor,
                      radius: 14,
                      child: Text(
                        "${job.jobOffersCount} ${LocalKeys.offers}",
                        style: context.bodySmall?.copyWith(
                            color: context.color.secondaryContrastColor,
                            fontWeight: FontWeight.w600),
                      ))
              ],
            )
          ],
        ),
      ),
    );
  }
}
