import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobDetailsTitleBudget extends StatelessWidget {
  final String title;
  final num budget;
  final num jobOffersCount;
  final bool? publishStatus;
  const JobDetailsTitleBudget(
      {super.key,
      required this.title,
      required this.budget,
      required this.jobOffersCount,
      required this.publishStatus});

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
            title,
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
                        text: budget.cur,
                        style: context.titleSmall?.bold.copyWith(
                          color: primaryColor,
                        ),
                      )
                    ]),
              ),
              if (jobOffersCount > 0)
                SquircleContainer(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    borderColor: context.color.primaryBorderColor,
                    radius: 14,
                    child: Text(
                      "$jobOffersCount ${LocalKeys.offers}",
                      style: context.bodySmall?.copyWith(
                          color: context.color.secondaryContrastColor),
                    ))
            ],
          ),
          if (publishStatus != null) ...[]
        ],
      ),
    );
  }
}
