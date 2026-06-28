import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class JobTileSkeleton extends StatelessWidget {
  final bool hideBorder;
  const JobTileSkeleton({super.key, this.hideBorder = false});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
      margin: hideBorder ? null : 25.paddingH,
      padding: hideBorder ? 24.paddingH : 16.paddingAll,
      radius: hideBorder ? null : 8,
      borderColor: context.color.primaryBorderColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          TextSkeleton(
            height: 16,
            width: context.width * .7,
          ),
          4.toHeight,
          TextSkeleton(
            height: 16,
            width: context.width * .4,
          ),
          6.toHeight,
          Row(
            children: [
              const TextSkeleton(
                height: 14,
                width: 26,
              ),
              6.toWidth,
              const TextSkeleton(
                height: 14,
                width: 64,
              ),
            ],
          ),
          Divider(
            color: context.color.primaryBorderColor,
            height: 24,
          ),
          Row(
            children: [
              const Expanded(
                flex: 1,
                child: Row(
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: 44,
                    ),
                  ],
                ),
              ),
              TextSkeleton(
                height: 14,
                width: context.width * .4,
              )
            ],
          )
        ],
      ),
    );
  }
}
