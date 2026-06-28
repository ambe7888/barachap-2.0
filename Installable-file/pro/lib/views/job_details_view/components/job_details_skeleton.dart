import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/text_skeleton.dart';
import 'package:prohand/views/order_details_view/components/client_tile_skeleton.dart';

import '../../../helper/constant_helper.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobDetailsSkeleton extends StatelessWidget {
  const JobDetailsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
        physics: const NeverScrollableScrollPhysics(),
        child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextSkeleton(
                  height: 16,
                  width: context.width * .7,
                ),
                const SizedBox(
                  height: 10,
                ),
                const Row(
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: 26,
                    ),
                    SizedBox(width: 6),
                    TextSkeleton(
                      height: 14,
                      width: 64,
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                TextSkeleton(
                  height: 16,
                  width: context.width * .3,
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const TextSkeleton(
                  height: 12,
                  width: 36,
                ),
                const SizedBox(
                  height: 10,
                ),
                Row(
                  children: [
                    TextSkeleton(
                      height: 16,
                      width: context.width * .2,
                    ),
                    const SizedBox(
                      width: 10,
                    ),
                    TextSkeleton(
                      height: 16,
                      width: context.width * .3,
                    ),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(
            height: 8,
          ),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const TextSkeleton(
                  height: 12,
                  width: 36,
                ),
                const SizedBox(
                  height: 10,
                ),
                TextSkeleton(
                  height: 16,
                  width: context.width * .8,
                ),
                const SizedBox(
                  height: 4,
                ),
                TextSkeleton(
                  height: 16,
                  width: context.width * .3,
                ),
              ],
            ),
          ),
          const SizedBox(
            height: 8,
          ),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextSkeleton(
                  height: 16,
                  width: context.width * .8,
                ),
                const SizedBox(
                  height: 4,
                ),
                TextSkeleton(
                  height: 16,
                  width: context.width * .75,
                ),
                const SizedBox(
                  height: 4,
                ),
                TextSkeleton(
                  height: 16,
                  width: context.width * .3,
                ),
              ],
            ),
          ),
          const SizedBox(
            height: 8,
          ),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              physics: const NeverScrollableScrollPhysics(),
              child: Wrap(
                spacing: 12,
                children: List.generate(
                  6,
                  (index) => SquircleContainer(
                      width: 96,
                      height: 80,
                      radius: 6,
                      color: color.mutedContrastColor,
                      child: const SizedBox()),
                ),
              ),
            ),
          ),
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              children: [
                const ClientTileSkeleton(
                  withPadding: false,
                ),
                Divider(
                  color: color.mutedContrastColor,
                  height: 36,
                ),
                const SizedBox(height: 8),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: context.width * .2,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: context.width * .3,
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: context.width * .1,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: context.width * .15,
                    ),
                  ],
                ),
              ],
            ),
          ),
        ]).shim);
  }
}
