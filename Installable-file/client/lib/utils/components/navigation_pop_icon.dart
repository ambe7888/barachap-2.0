import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';

import '/helper/extension/context_extension.dart';

class NavigationPopIcon extends StatelessWidget {
  final void Function()? onTap;
  final bool isFloating;
  const NavigationPopIcon({this.onTap, this.isFloating = false, super.key});

  @override
  Widget build(BuildContext context) {
    return Transform.rotate(
      angle: context.dProvider.textDirectionRight ? pi : 0,
      child: GestureDetector(
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
                  angle: context.dProvider.textDirectionRight ? pi : 0,
                  child: Icon(
                    Icons.chevron_left_outlined,
                    size: 32,
                    color: context.color.primaryContrastColor,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
