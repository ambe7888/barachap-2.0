import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/text_skeleton.dart';
import 'package:prohandy_client/views/order_details_view/components/provider_tile_skeleton.dart';

import '../../../helper/constant_helper.dart';

class OfferListSkeleton extends StatelessWidget {
  const OfferListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const NeverScrollableScrollPhysics(),
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Wrap(
        runSpacing: 8,
        children: List.generate(
          10,
          (index) => Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    TextSkeleton(
                      height: 18,
                      width: 120,
                    ),
                    TextSkeleton(
                      height: 14,
                      width: 64,
                    ),
                  ],
                ),
                Divider(
                  color: color.primaryBorderColor,
                  height: 24,
                  thickness: 2,
                ),
                const ProviderTileSkeleton(
                  withPadding: false,
                ),
              ],
            ),
          ),
        ),
      ).shim,
    );
  }
}
