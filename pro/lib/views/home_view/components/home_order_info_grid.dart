import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/views/home_view/components/order_grid_slot.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../services/theme_service.dart';
import 'home_order_info_grid_skeleton.dart';

class HomeOrderInfoGrid extends StatelessWidget {
  const HomeOrderInfoGrid({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<DashboardInfoService>(builder: (context, di, child) {
        return CustomFutureWidget(
          isLoading: di.isLoading,
          shimmer: const HomeOrderInfoGridSkeleton(),
          child: Container(
            color: context.color.accentContrastColor,
            padding: 24.paddingH,
            child: Wrap(
              spacing: 12,
              runSpacing: 12,
              children: [
                Row(
                  children: [8.toHeight],
                ),
                OrderGridSlot(
                  title: "${di.dashboardInfoModel?.pendingOrder ?? 0}",
                  description: LocalKeys.pendingOrders,
                  borderColor: gridColors[0],
                  color: gridColors[0].withOpacity(.07),
                ),
                OrderGridSlot(
                  title: "${di.dashboardInfoModel?.activeOrder ?? 0}",
                  description: LocalKeys.orderInProgress,
                  borderColor: gridColors[1],
                  color: gridColors[1].withOpacity(.07),
                ),
                OrderGridSlot(
                  title: "${di.dashboardInfoModel?.completedOrder ?? 0}",
                  description: LocalKeys.ordersCompleted,
                  borderColor: gridColors[2],
                  color: gridColors[2].withOpacity(.07),
                ),
                OrderGridSlot(
                  title: "${di.totalOrder}",
                  description: LocalKeys.totalOrders,
                  borderColor: gridColors[3],
                  color: gridColors[3].withOpacity(.07),
                ),
                Row(
                  children: [8.toHeight],
                )
              ],
            ),
          ),
        );
      });
    });
  }
}
