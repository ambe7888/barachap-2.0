import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../utils/components/custom_squircle_widget.dart';
import '../../../utils/components/text_skeleton.dart';

class ServiceCardSkeleton extends StatelessWidget {
  const ServiceCardSkeleton({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
      width: 188,
      padding: const EdgeInsets.all(8),
      radius: 16,
      borderColor: context.color.primaryBorderColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SquircleContainer(
              width: 172,
              height: 128,
              radius: 8,
              color: context.color.mutedContrastColor,
              child: const SizedBox()),
          12.toHeight,
          Row(
            children: [
              const TextSkeleton(
                height: 14,
                width: 26,
              ),
              6.toWidth,
              const TextSkeleton(
                height: 14,
                width: 36,
              ),
            ],
          ),
          12.toHeight,
          const TextSkeleton(
            height: 14,
            width: 156,
          ),
          4.toHeight,
          const TextSkeleton(
            height: 14,
            width: 100,
          ),
          12.toHeight,
          const TextSkeleton(
            height: 14,
            width: 64,
          ),
          16.toHeight,
          Divider(
            height: 2,
            color: context.color.mutedContrastColor,
            thickness: 2,
          ),
          10.toHeight,
          Row(
            children: [
              CircleAvatar(
                radius: 18,
                backgroundColor: primaryColor.withOpacity(.5),
              ),
              const SizedBox(
                width: 4,
              ),
              const TextSkeleton(
                height: 12,
                width: 88,
              )
            ],
          )
        ],
      ),
    );
  }
}
