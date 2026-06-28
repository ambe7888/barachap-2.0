import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/job/job_details_model.dart';
import 'package:readmore/readmore.dart';

import '../../../helper/local_keys.g.dart';

class JobDetailsOffer extends StatelessWidget {
  final JobOffer offer;

  const JobDetailsOffer({super.key, required this.offer});

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
            LocalKeys.hired,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.titleLarge
                ?.copyWith(color: context.color.tertiaryContrastColo)
                .bold,
          ),
          6.toHeight,
          Text(
            offer.budget.cur,
            style: context.titleLarge?.copyWith(color: primaryColor).bold,
          ),
          if (offer.coverLetter?.isNotEmpty ?? false) ...[
            Divider(
              height: 24,
              thickness: 2,
              color: context.color.mutedContrastColor,
            ),
            ReadMoreText(
              offer.coverLetter!,
              trimLines: 3,
              trimCollapsedText: LocalKeys.showMore,
              trimExpandedText: LocalKeys.showLess,
              style: context.bodySmall,
              colorClickableText: primaryColor,
            )
          ]
        ],
      ),
    );
  }
}
