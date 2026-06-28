import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class JobTileIdStatus extends StatelessWidget {
  final dynamic jobId;
  final String? jobStatus;
  const JobTileIdStatus({super.key, this.jobId, required this.jobStatus});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 8,
      crossAxisAlignment: WrapCrossAlignment.center,
      children: [
        Text(
          "#$jobId",
          style: context.bodySmall?.bold.copyWith(color: primaryColor),
        ),
        if (jobStatus != null)
          SquircleContainer(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              color: jobStatus!.getJobMutedStatusColor,
              radius: 4,
              child: Text(
                jobStatus!.getJobStatus,
                style: context.bodySmall
                    ?.copyWith(color: jobStatus!.getJobPrimaryStatusColor),
              ))
      ],
    );
  }
}
