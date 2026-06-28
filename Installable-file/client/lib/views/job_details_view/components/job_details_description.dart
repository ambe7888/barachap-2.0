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
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
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
