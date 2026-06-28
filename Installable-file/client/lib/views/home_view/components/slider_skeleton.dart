import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';

class SliderSkeleton extends StatelessWidget {
  const SliderSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(
        vertical: 16,
      ),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
      ),
      child: SquircleContainer(
              margin: const EdgeInsets.symmetric(horizontal: 8.0),
              width: context.width * 0.82,
              height: ((context.width - 24) / 307) * 150,
              radius: 12,
              color: context.color.mutedContrastColor,
              child: const SizedBox())
          .shim,
    );
  }
}
