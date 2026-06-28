import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

class InfoTile extends StatelessWidget {
  final String title;
  final String value;
  final double? fontSize;
  final Color? valueColor;
  const InfoTile(
      {super.key,
      required this.title,
      required this.value,
      this.fontSize,
      this.valueColor});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
            flex: 1,
            child: Text(
              title,
              style: context.bodySmall?.bold5.copyWith(
                color: context.color.secondaryContrastColor,
              ),
            )),
        12.toWidth,
        ConstrainedBox(
            constraints: BoxConstraints(maxWidth: (context.width - 52) / 2),
            child: Text(
              value,
              style: context.titleSmall?.bold.copyWith(
                fontSize: fontSize,
                color: valueColor,
              ),
            )),
      ],
    );
  }
}
