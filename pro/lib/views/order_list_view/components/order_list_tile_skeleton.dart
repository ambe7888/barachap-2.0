import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

class OrderListTileSkeleton extends StatelessWidget {
  const OrderListTileSkeleton({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      padding: const EdgeInsets.all(16),
      radius: 10,
      borderColor: context.color.primaryBorderColor,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SquircleContainer(
            height: 72,
            width: 72,
            radius: 10,
            color: context.color.mutedContrastColor,
            child: const SizedBox(),
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Wrap(
                  spacing: 6,
                  runSpacing: 6,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: 24,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: 60,
                    ),
                  ],
                ),
                6.toHeight,
                const TextSkeleton(
                  height: 14,
                  width: 180,
                ),
                8.toHeight,
                const Wrap(
                  spacing: 6,
                  runSpacing: 6,
                  crossAxisAlignment: WrapCrossAlignment.center,
                  children: [
                    TextSkeleton(
                      height: 18,
                      width: 60,
                    ),
                    TextSkeleton(
                      height: 18,
                      width: 72,
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
