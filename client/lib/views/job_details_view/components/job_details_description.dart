import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:readmore/readmore.dart';

import '../../../helper/local_keys.g.dart';

class JobDetailsDescription extends StatelessWidget {
  final String description;
  const JobDetailsDescription({
    super.key,
    required this.description,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
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
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ReadMoreText(
            description,
            trimMode: TrimMode.Line,
            trimLines: 3,
            colorClickableText: primaryColor,
            trimCollapsedText: LocalKeys.showMore,
            trimExpandedText: LocalKeys.showLess,
            style: context.titleSmall,
          )
        ],
      ),
    );
  }
}
