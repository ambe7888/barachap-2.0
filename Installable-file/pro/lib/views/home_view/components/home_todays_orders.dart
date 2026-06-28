import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/services/order_services/todays_order_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:provider/provider.dart';

import 'home_assigned_staffs.dart';
import 'home_order_in_progress.dart';
import 'home_todays_orders_skeleton.dart';

class HomeTodaysOrders extends StatelessWidget {
  const HomeTodaysOrders({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<TodaysOrdersService>(builder: (context, tdo, child) {
      return CustomFutureWidget(
          function: tdo.shouldAutoFetch ? tdo.fetchOrder() : null,
          shimmer: const HomeTodaysOrdersSkeleton(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              8.toHeight,
              const HomeOrderInProgress(),
              if (tdo.staffs.isNotEmpty) ...[
                8.toHeight,
                const HomeAssignedStaffs()
              ],
            ],
          ));
    });
  }
}
