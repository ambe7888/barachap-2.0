import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';

class ServiceDetailsCancellationPolicy extends StatelessWidget {
  final String? description;
  const ServiceDetailsCancellationPolicy({super.key, this.description});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CircleAvatar(
                radius: 26,
                backgroundColor: context.color.mutedWarningColor,
                child: SvgAssets.verified.toSVGSized(
                  24,
                  color: context.color.primaryWarningColor,
                ),
              ),
              12.toWidth,
              Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      LocalKeys.cancellationPolicy,
                      style: context.titleMedium?.bold,
                    ),
                    4.toHeight,
                    ReadMoreText(
                      description ?? "",
                      style: context.bodySmall,
                      trimMode: TrimMode.Line,
                      trimLines: 2,
                      colorClickableText: primaryColor,
                      trimCollapsedText: LocalKeys.showMore,
                      trimExpandedText: " ${LocalKeys.showLess}",
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
