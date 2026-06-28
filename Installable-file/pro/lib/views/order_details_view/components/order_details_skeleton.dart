import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/order_details_view/components/od_service_tile_skelton.dart';

import '../../../helper/constant_helper.dart';
import '../../../utils/components/text_skeleton.dart';
import 'client_tile_skeleton.dart';

class OrderDetailsSkeleton extends StatelessWidget {
  const OrderDetailsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const NeverScrollableScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(height: 8),
          const OdServiceTileSkelton(),
          const SizedBox(height: 8),
          const ClientTileSkeleton(),
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
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextSkeleton(
                  height: 14,
                  width: context.width * .2,
                ),
                const SizedBox(height: 8),
                TextSkeleton(
                  height: 16,
                  width: context.width * .6,
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
                TextSkeleton(
                  height: 14,
                  width: context.width * .2,
                ),
                const SizedBox(height: 8),
                TextSkeleton(
                  height: 16,
                  width: context.width * .4,
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                TextSkeleton(
                  height: 16,
                  width: context.width * .2,
                ),
                const SizedBox(height: 8),
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
                TextSkeleton(
                  height: 14,
                  width: context.width * .2,
                ),
                const SizedBox(height: 8),
                TextSkeleton(
                  height: 16,
                  width: context.width * .8,
                ),
                const SizedBox(height: 8),
                TextSkeleton(
                  height: 16,
                  width: context.width * .6,
                ),
                const SizedBox(height: 8),
                TextSkeleton(
                  height: 16,
                  width: context.width * .5,
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
                TextSkeleton(
                  height: 16,
                  width: context.width * .2,
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    CircleAvatar(
                      radius: 22,
                      backgroundColor: color.mutedContrastColor,
                    ),
                    const SizedBox(width: 8),
                    const TextSkeleton(
                      height: 16,
                      width: 88,
                    )
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
        ],
      ).shim,
    );
  }
}
