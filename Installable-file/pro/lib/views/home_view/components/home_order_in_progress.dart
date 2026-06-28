import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/order_services/todays_order_service.dart';
import 'package:provider/provider.dart';

import '../../../services/theme_service.dart';
import 'order_in_progress_tile.dart';

class HomeOrderInProgress extends StatelessWidget {
  const HomeOrderInProgress({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<TodaysOrdersService>(builder: (context, ts, child) {
        return Container(
          padding: 16.paddingV,
          width: double.infinity,
          color: context.color.accentContrastColor,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(LocalKeys.ordersInProgressToday,
                      style: context.headlineLarge?.bold)
                  .hp20,
              12.toHeight,
              if (ts.myOrdersModel.allOrders.isEmpty)
                Text(LocalKeys.noOrdersForToday, style: context.bodySmall).hp20,
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                padding: 24.paddingH,
                child: Wrap(
                  spacing: 12,
                  children: ts.myOrdersModel.allOrders.map((order) {
                    return OrderInProgressTile(
                      order: order,
                    );
                  }).toList(),
                ),
              )
            ],
          ),
        );
      });
    });
  }
}
