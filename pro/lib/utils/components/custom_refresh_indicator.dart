import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';

import '/helper/extension/context_extension.dart';

class CustomRefreshIndicator extends StatelessWidget {
  final child;
  final onRefresh;
  final GlobalKey<RefreshIndicatorState>? refreshKey;
  const CustomRefreshIndicator(
      {super.key, this.child, this.onRefresh, this.refreshKey});

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
        key: refreshKey,
        backgroundColor: context.color.mutedContrastColor,
        color: primaryColor,
        child: child,
        onRefresh: onRefresh);
  }
}
