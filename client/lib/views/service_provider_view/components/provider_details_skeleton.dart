import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/views/order_details_view/components/provider_tile_skeleton.dart';

import '../../../helper/constant_helper.dart';
import '../../../utils/components/text_skeleton.dart';

class ProviderDetailsSkeleton extends StatelessWidget {
  const ProviderDetailsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const NeverScrollableScrollPhysics(),
      padding: 8.paddingV,
      child: Column(
        children: [
          const ProviderTileSkeleton(),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Padding(
                  padding: EdgeInsets.symmetric(horizontal: 24),
                  child: Wrap(
                    spacing: 12,
                    children: [
                      TextSkeleton(
                        height: 16,
                        width: 60,
                      ),
                      TextSkeleton(
                        height: 16,
                        width: 40,
                      ),
                      TextSkeleton(
                        height: 16,
                        width: 48,
                      ),
                    ],
                  ),
                ),
                16.toHeight,
                const SizedBox().divider,
                16.toHeight,
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 24),
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
              ],
            ),
          ),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 24),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextSkeleton(
                  height: 14,
                  width: context.width * .2,
                ),
                SizedBox(
                  height: 36,
                  child: ListView.separated(
                      scrollDirection: Axis.horizontal,
                      physics: const NeverScrollableScrollPhysics(),
                      itemBuilder: (context, index) => Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                                border: Border(
                              bottom: BorderSide(
                                color: context.color.primaryBorderColor,
                              ),
                            )),
                            child: const TextSkeleton(
                              height: 14,
                              width: 56,
                            ),
                          ),
                      separatorBuilder: (context, index) => const SizedBox(),
                      itemCount: 7),
                ),
                const SizedBox(height: 12),
                Wrap(
                  spacing: 16,
                  runSpacing: 8,
                  children: List.generate(
                    6,
                    (index) => SquircleContainer(
                        height: 40,
                        width: context.width * .4,
                        radius: 10,
                        color: color.mutedContrastColor,
                        child: const SizedBox()),
                  ),
                )
              ],
            ),
          )
        ],
      ).shim,
    );
  }
}
