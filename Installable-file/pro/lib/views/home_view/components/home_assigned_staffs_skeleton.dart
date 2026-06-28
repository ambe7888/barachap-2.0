import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../utils/components/text_skeleton.dart';

class HomeAssignedStaffsSkeleton extends StatelessWidget {
  const HomeAssignedStaffsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: 16.paddingV,
      width: double.infinity,
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          TextSkeleton(
            height: 20,
            width: context.width * .4,
          ).hp20,
          12.toHeight,
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: 24.paddingH,
            child: Wrap(
              spacing: 12,
              children: List.generate(4, (staff) {
                return SquircleContainer(
                  height: 44,
                  width: 44,
                  radius: 22,
                  color: context.color.mutedContrastColor,
                  child: const SizedBox(),
                );
              }),
            ),
          )
        ],
      ),
    );
  }
}
