import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';

class TextSkeleton extends StatelessWidget {
  final height;
  final width;
  final color;
  final double? radius;
  const TextSkeleton(
      {this.height, this.width, super.key, this.color, this.radius});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: width?.toDouble(),
      height: height?.toDouble(),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(radius ?? 4),
        color: color ?? context.color.mutedContrastColor,
      ),
    );
  }
}
