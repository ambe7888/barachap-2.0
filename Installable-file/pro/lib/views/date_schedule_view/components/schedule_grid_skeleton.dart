import 'package:flutter/material.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

class ScheduleGridSkeleton extends StatelessWidget {
  const ScheduleGridSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 8,
      runSpacing: 16,
      children: List.generate(
        7,
        (index) => SquircleContainer(
            height: 40,
            radius: 10,
            borderColor: color.primaryBorderColor,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                TextSkeleton(
                  height: 14,
                  width: context.width * .35,
                ),
              ],
            )),
      ),
    ).shim;
  }
}
