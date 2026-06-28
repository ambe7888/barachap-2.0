import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';

class SubOrderNote extends StatelessWidget {
  final String note;
  const SubOrderNote({super.key, required this.note});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.note,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                6.toHeight,
                ReadMoreText(
                  note,
                  trimMode: TrimMode.Line,
                  trimLines: 3,
                  colorClickableText: primaryColor,
                  trimCollapsedText: LocalKeys.showMore,
                  trimExpandedText: " ${LocalKeys.showLess}",
                  style: context.bodyMedium,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
