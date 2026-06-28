import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/text_skeleton.dart';
import 'package:prohandy_client/views/order_details_view/components/provider_tile_skeleton.dart';

import '../../../helper/constant_helper.dart';

class OfferDetailsSkeleton extends StatelessWidget {
  const OfferDetailsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const NeverScrollableScrollPhysics(),
      padding: 8.paddingV,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: const Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                TextSkeleton(
                  height: 36,
                  width: 150,
                ),
                TextSkeleton(
                  height: 14,
                  width: 88,
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          const ProviderTileSkeleton(),
          const SizedBox(height: 8),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              children: [
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
                Divider(
                  color: color.mutedContrastColor,
                  height: 36,
                ),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: context.width * .3,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: context.width * .2,
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextSkeleton(
                      height: 14,
                      width: context.width * .2,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: context.width * .2,
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
                const TextSkeleton(
                  height: 18,
                  width: 64,
                ),
                const SizedBox(height: 16),
                TextSkeleton(
                  height: 14,
                  width: context.width * .9,
                ),
                const SizedBox(height: 4),
                TextSkeleton(
                  height: 14,
                  width: context.width * .8,
                ),
                const SizedBox(height: 4),
                TextSkeleton(
                  height: 14,
                  width: context.width * .6,
                ),
              ],
            ),
          )
        ],
      ).shim,
    );
  }
}
