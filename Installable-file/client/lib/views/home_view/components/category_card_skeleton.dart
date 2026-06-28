import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/text_skeleton.dart';

class CategoryCardSkeleton extends StatelessWidget {
  const CategoryCardSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        SquircleContainer(
          height: 64,
          width: 64,
          radius: 16,
          color: context.color.mutedContrastColor,
          child: const SizedBox(),
        ),
        const SizedBox(height: 10),
        const TextSkeleton(
          width: 52,
          height: 15,
        ),
      ],
    );
  }
}
