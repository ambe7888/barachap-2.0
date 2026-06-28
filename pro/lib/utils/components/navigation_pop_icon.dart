import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '/helper/extension/context_extension.dart';
import '../../helper/svg_assets.dart';

class NavigationPopIcon extends StatelessWidget {
  final void Function()? onTap;
  final bool isFloating;
  const NavigationPopIcon({this.onTap, this.isFloating = false, super.key});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        if (onTap == null) {
          context.popTrue;
          return;
        }
        onTap!();
      },
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          SquircleContainer(
            height: 32,
            width: 32,
            color: context.color.accentContrastColor,
            radius: 16,
            child: Center(
              child: Transform.rotate(
                angle: context.dProvider.textDirectionRight ? 0 : pi,
                child: SvgAssets.chevron.toSVGSized(
                  24,
                  color: context.color.secondaryContrastColor,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
