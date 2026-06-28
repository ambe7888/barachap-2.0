import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

class OrderGridSlot extends StatelessWidget {
  final String title;
  final String description;
  final Color? color;
  final Color? borderColor;

  const OrderGridSlot(
      {super.key,
      required this.title,
      required this.description,
      this.color,
      this.borderColor});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        radius: 10,
        color: color,
        width: (context.width - 60) / 2,
        borderColor: borderColor?.withOpacity(.5),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: context.headlineLarge?.bold.copyWith(color: borderColor),
            ),
            Text(
              description,
              style: context.bodySmall?.bold.copyWith(color: borderColor),
            ),
          ],
        ));
  }
}
