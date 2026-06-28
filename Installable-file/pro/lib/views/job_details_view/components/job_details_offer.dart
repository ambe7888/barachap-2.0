import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/job_models/job_details_model.dart';
import 'package:readmore/readmore.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobDetailsOffer extends StatelessWidget {
  final JobDetails jobDetails;
  final JobDetailsModel jobDetailsModel;

  const JobDetailsOffer({
    super.key,
    required this.jobDetails,
    required this.jobDetailsModel,
  });

  @override
  Widget build(BuildContext context) {
    final offer = jobDetailsModel.jobOffer;
    return offer == null
        ? const SizedBox()
        : Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.offer,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleLarge
                      ?.copyWith(color: context.color.tertiaryContrastColo)
                      .bold,
                ),
                6.toHeight,
                Wrap(
                  spacing: 6,
                  children: [
                    Text(
                      offer.budget.cur,
                      style: context.titleLarge
                          ?.copyWith(color: primaryColor)
                          .bold,
                    ),
                    SquircleContainer(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 6, vertical: 2),
                        borderColor: (offer.status
                            .toString()
                            .getOfferPrimaryStatusColor),
                        radius: 6,
                        child: Text(
                          offer.status.toString().getOfferStatus,
                          style: context.bodySmall?.copyWith(
                            color: offer.status
                                .toString()
                                .getOfferPrimaryStatusColor,
                          ),
                        )),
                  ],
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
                  )
                ]
              ],
            ),
          );
  }
}
