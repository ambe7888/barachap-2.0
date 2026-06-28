import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/field_label.dart';

import '../../../helper/svg_assets.dart';

class LabelWithSeeAll extends StatelessWidget {
  final String label;
  final void Function()? onPressed;
  const LabelWithSeeAll({
    super.key,
    required this.label,
    this.onPressed,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          FieldLabel(
            label: label,
          ),
          TextButton.icon(
            onPressed: onPressed,
            label: Text(
              LocalKeys.seeAll,
            ),
            icon: Transform.rotate(
              angle: context.dProvider.textDirectionRight ? pi : 0,
              child: SvgAssets.chevron.toSVGSized(
                20,
                color: context.color.secondaryContrastColor,
              ),
            ),
            iconAlignment: IconAlignment.end,
          )
        ],
      ),
    );
  }
}
