import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';

import '../../../helper/svg_assets.dart';

class MenuTile extends StatelessWidget {
  final String title;
  final String svg;
  final void Function()? onPress;
  final bool haveDivider;
  final Widget? trailing;
  final Color? titleColor;
  final Color? iconColor;
  final double opacity;
  const MenuTile(
      {super.key,
      required this.title,
      required this.svg,
      this.onPress,
      this.trailing,
      this.haveDivider = false,
      this.titleColor,
      this.iconColor,
      this.opacity = 1.0});

  @override
  Widget build(BuildContext context) {
    Widget content = GestureDetector(
      onTap: onPress,
      child: Column(
        children: [
          Container(
            margin: const EdgeInsets.only(bottom: 2),
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            decoration: BoxDecoration(
              color: context.color.accentContrastColor,
            ),
            child: Row(children: [
              svg.toSVGSized(20, color: iconColor ?? context.color.tertiaryContrastColo),
              12.toWidth,
              Expanded(
                flex: 1,
                child: Text(
                  title,
                  style: context.titleMedium?.bold6
                      .copyWith(color: titleColor ?? context.color.primaryContrastColor),
                ),
              ),
              12.toWidth,
              if (onPress != null)
                Transform.rotate(
                  angle: context.dProvider.textDirectionRight ? pi : 0,
                  child: SvgAssets.chevron.toSVGSized(
                    20,
                    color: context.color.secondaryContrastColor,
                  ),
                ),
              if (trailing != null) trailing!
            ]),
          ),
          if (haveDivider)
            Padding(
              padding: 24.paddingH,
              child: const SizedBox().divider,
            )
        ],
      ),
    );
    return opacity == 1.0 ? content : Opacity(opacity: opacity, child: content);
  }
}
