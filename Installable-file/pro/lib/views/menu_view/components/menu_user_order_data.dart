import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:provider/provider.dart';

import '../../../services/profile_services/dashboard_info_service.dart';

class MenuUserOrderData extends StatelessWidget {
  final ThemeService ts;
  const MenuUserOrderData({super.key, required this.ts});

  @override
  Widget build(BuildContext context) {
    return Consumer<DashboardInfoService>(builder: (context, di, child) {
      return Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: ts.selectedTheme.accentContrastColor,
        child: Row(
          children: [
            OrderDataBlock(
                value:
                    "${(di.dashboardInfoModel?.orderCompletionRate ?? 0).round()}%",
                label: LocalKeys.completionRate),
            Container(
              width: 2,
              height: 36,
              margin: 18.paddingH,
              color: context.color.primaryBorderColor,
            ),
            OrderDataBlock(
                value:
                    "${(di.dashboardInfoModel?.customerSatisfactionRate ?? 0).round()}%",
                label: LocalKeys.clientRatings),
            Container(
              width: 2,
              height: 36,
              margin: 18.paddingH,
              color: context.color.primaryBorderColor,
            ),
            OrderDataBlock(
                value: "${di.totalOrder}", label: LocalKeys.totalOrders),
          ],
        ),
      );
    });
  }
}

class OrderDataBlock extends StatelessWidget {
  const OrderDataBlock({
    super.key,
    required this.value,
    required this.label,
  });

  final String value;
  final String label;

  @override
  Widget build(BuildContext context) {
    return Expanded(
      flex: 1,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            value,
            style: context.titleLarge?.bold,
          ),
          Text(
            label,
            style: context.bodySmall?.copyWith(
              color: context.color.secondaryContrastColor,
            ),
          ),
        ],
      ),
    );
  }
}
