// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/view_models/home_view_model/unread_count_service.dart';
import 'package:prohand/views/home_view/components/home_menu_button.dart';
import 'package:prohand/views/home_view/components/home_order_info_grid.dart';
import 'package:prohand/views/home_view/components/home_todays_orders.dart';
import 'package:provider/provider.dart';

import '../../services/order_services/todays_order_service.dart';
import '../../services/theme_service.dart';
import '../../view_models/home_view_model/home_view_model.dart';
import 'components/customer_satisfaction_rate.dart';
import 'components/home_app_bar.dart';
import 'components/home_bar_chart.dart';
import 'components/home_drawer.dart';
import 'components/home_new_order.dart';
import 'components/home_service_create_banner.dart';
import 'components/order_completion_rate.dart';

class HomeView extends StatelessWidget {
  const HomeView({super.key});

  @override
  Widget build(BuildContext context) {
    final hm = HomeViewModel.instance;
    UnreadCountService.instance.fetchUnreadCounts();
    return Consumer<ThemeService>(
      builder: (context, ts, child) {
        return Scaffold(
          key: hm.scaffoldKey,
          appBar: AppBar(
            leading: const HomeMenuButton(),
            title: const HomeAppBar(),
          ),
          drawer: const HomeDrawer(),
          body: CustomRefreshIndicator(
            onRefresh: () async {
              UnreadCountService.instance.fetchUnreadCounts();
              await Provider.of<DashboardInfoService>(
                context,
                listen: false,
              ).fetchDashboardInfo(refreshing: true);
              await Provider.of<TodaysOrdersService>(
                context,
                listen: false,
              ).fetchOrder(refresh: true);
            },
            child: SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Column(
                children: [
                  if (1 == 2) const HomeNewOrder(),
                  const HomeOrderInfoGrid(),
                  const HomeTodaysOrders(),
                  8.toHeight,
                  const OrderCompletionRate(),
                  8.toHeight,
                  const CustomerSatisfactionRate(),
                  8.toHeight,
                  const HomeServiceCreateBanner(),
                  16.toHeight,
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
