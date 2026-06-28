import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';

class OrderDetailsNote extends StatelessWidget {
  final String? note;
  final bool fromAccept;
  const OrderDetailsNote({
    super.key,
    required this.note,
    this.fromAccept = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.orderNote,
                  style: context.titleMedium?.bold
                      .copyWith(color: context.color.tertiaryContrastColo),
                ),
                8.toHeight,
                ReadMoreText(
                  note!,
                  trimMode: TrimMode.Line,
                  trimLines: fromAccept ? 10 : 1,
                  colorClickableText: primaryColor,
                  trimCollapsedText: LocalKeys.showMore,
                  trimExpandedText: " ${LocalKeys.showLess}",
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
