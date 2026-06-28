import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/job_models/job_details_model.dart';
import 'package:prohand/utils/components/info_tile.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobDetailsClientTile extends StatelessWidget {
  final JobDetails jobDetails;
  const JobDetailsClientTile({super.key, required this.jobDetails});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.accentContrastColor,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CustomNetworkImage(
                height: 48,
                width: 48,
                radius: 24,
                imageUrl: jobDetails.client?.image,
                fit: BoxFit.cover,
                name: jobDetails.client?.fullname,
                userPreloader: true,
              ),
              8.toWidth,
              Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Wrap(
                        spacing: 6,
                        runSpacing: 4,
                        crossAxisAlignment: WrapCrossAlignment.center,
                        children: [
                          Text(
                            jobDetails.client?.fullname != null
                                ? jobDetails.client!.fullname!
                                : LocalKeys.noName,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: context.titleMedium?.bold,
                          ),
                          if ((jobDetails.client?.reviewCount ?? 0) > 0) ...[
                            SquircleContainer(
                                padding: const EdgeInsets.symmetric(
                                    vertical: 4, horizontal: 6),
                                color: context.color.mutedPendingColor,
                                radius: 8,
                                child: FittedBox(
                                  child: Row(
                                    children: [
                                      Icon(
                                        Icons.star_rounded,
                                        size: 18,
                                        color:
                                            context.color.primaryPendingColor,
                                      ),
                                      2.toWidth,
                                      Text(
                                        "${jobDetails.client!.averageRating.toStringAsFixed(1)} (${jobDetails.client!.reviewCount.toString()})",
                                        style: context.bodySmall
                                            ?.copyWith(
                                              color: context
                                                  .color.primaryPendingColor,
                                            )
                                            .bold5,
                                      ),
                                    ],
                                  ),
                                ))
                          ],
                        ],
                      ),
                      4.toHeight,
                      Text(
                        "${LocalKeys.memberSince} ${DateFormat("yyyy").format(jobDetails.client!.createdAt ?? DateTime.now())}",
                        style: context.bodySmall?.copyWith(
                            color: context.color.secondaryContrastColor),
                      ),
                    ],
                  )),
            ],
          ),
        ),
        const SizedBox().divider,
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.accentContrastColor,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(
                  title: LocalKeys.jobPosted,
                  value: "${jobDetails.client!.clientTotalJobs}"),
              12.toHeight,
              InfoTile(
                  title: LocalKeys.lastSeen,
                  value: timeago.format(jobDetails.client!.clientLastSeen ??
                      DateTime.now().subtract(3256.minutes))),
            ],
          ),
        ),
      ],
    );
  }
}
