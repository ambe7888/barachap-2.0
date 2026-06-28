import 'package:flutter/material.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '/helper/local_keys.g.dart';
import '/utils/components/empty_spacer_helper.dart';

class BalanceBox extends StatelessWidget {
  final num balance;
  const BalanceBox({
    super.key,
    required this.balance,
  });

  @override
  Widget build(BuildContext context) {
    return CustomPaint(
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            borderRadius: const BorderRadius.only(
                topLeft: Radius.circular(8), topRight: Radius.circular(8))),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              balance.toStringAsFixed(2).cur,
              overflow: TextOverflow.ellipsis,
              style: context.titleLarge?.bold6,
            ),
            EmptySpaceHelper.emptyHeight(4),
            Text(LocalKeys.balance),
          ],
        ),
      ),
    );
  }
}
