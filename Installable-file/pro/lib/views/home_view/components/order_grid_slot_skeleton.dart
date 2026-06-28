import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

class OrderGridSlotSkeleton extends StatelessWidget {
  final Color? color;
  final Color? borderColor;

  const OrderGridSlotSkeleton({super.key, this.color, this.borderColor});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        radius: 10,
        color: color,
        width: (context.width - 64) / 2,
        borderColor: borderColor?.withOpacity(.5),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            TextSkeleton(
              height: 24,
              width: 0,
              color: borderColor,
            ),
            4.toHeight,
            TextSkeleton(
              height: 12,
              width: 0,
              color: borderColor,
            ),
          ],
        ));
  }
}
