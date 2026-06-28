import 'package:flutter/material.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

class StaffAvatarListSkeleton extends StatelessWidget {
  const StaffAvatarListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 12,
      children: List.generate(3, (i) {
        return SquircleContainer(
            color: color.mutedContrastColor,
            height: 64,
            width: 64,
            radius: 32,
            child: SizedBox());
      }),
    ).shim;
  }
}
