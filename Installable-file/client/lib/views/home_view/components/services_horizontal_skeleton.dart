import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/text_skeleton.dart';

import 'service_card_skeleton.dart';

class ServicesHorizontalSkeleton extends StatelessWidget {
  final bool showTitle;
  const ServicesHorizontalSkeleton({super.key, this.showTitle = true});

  @override
  Widget build(BuildContext context) {
    return Container(
      color: context.color.accentContrastColor,
      margin: const EdgeInsets.only(top: 8),
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (showTitle) ...[
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: TextSkeleton(
                height: 16,
                width: context.width * .25,
              ),
            ),
            12.toHeight
          ],
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Wrap(
              spacing: 16,
              children: List.generate(
                5,
                (index) {
                  return const ServiceCardSkeleton();
                },
              ),
            ),
          ),
        ],
      ).shim,
    );
  }
}
