import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

class WarningWidget extends StatelessWidget {
  final text;
  const WarningWidget({
    super.key,
    required this.text,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
          color: context.color.mutedPendingColor,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: context.color.mutedPendingColor)),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
              flex: 3,
              child: Icon(
                Icons.warning_amber_rounded,
                color: context.color.primaryPendingColor,
                size: 40,
              )),
          const Expanded(flex: 1, child: SizedBox()),
          Expanded(
            flex: 16,
            child: Text(
              text,
              style: context.titleSmall
                  ?.copyWith(color: context.color.primaryPendingColor),
            ),
          ),
        ],
      ),
    );
  }
}
