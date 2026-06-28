import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:prohandy_client/views/order_details_view/components/order_details_buttons.dart';
import 'package:prohandy_client/views/order_details_view/components/order_details_cost_info.dart';
import 'package:prohandy_client/views/order_details_view/components/order_details_sub_orders.dart';
import 'package:provider/provider.dart';

import '../../customizations/colors.dart';
import '../../helper/local_keys.g.dart';
import '../../services/order_services/order_details_service.dart';
import '../../utils/components/empty_widget.dart';
import 'components/order_details_skeleton.dart';

class OrderDetailsView extends StatelessWidget {
  final dynamic orderId;
  const OrderDetailsView({super.key, this.orderId});

  @override
  Widget build(BuildContext context) {
    final olm = OrderListViewModel.instance;
    return Consumer<OrderDetailsService>(builder: (context, od, child) {
      return Scaffold(
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(
            "#$orderId",
            style: context.titleLarge?.bold.copyWith(color: primaryColor),
          ),
        ),
        body: CustomRefreshIndicator(
          key: olm.orderDetailsRK,
          onRefresh: () async {
            await od.fetchOrderDetails(orderId: orderId);
          },
          child: CustomFutureWidget(
            function: od.shouldAutoFetch(orderId)
                ? od.fetchOrderDetails(orderId: orderId)
                : null,
            shimmer: const OrderDetailsSkeleton1(),
            child: od.orderDetailsModel.orderDetails?.id == null
                ? EmptyWidget(title: LocalKeys.orderNotFound)
                : Scrollbar(
                    child: SingleChildScrollView(
                      padding: 8.paddingV,
                      physics: const AlwaysScrollableScrollPhysics(),
                      child: Column(
                        children: [
                          OrderDetailsSubOrders(od: od),
                          8.toHeight,
                          OrderDetailsCostInfo(od: od)
                        ],
                      ),
                    ),
                  ),
          ),
        ),
        bottomNavigationBar: od.orderDetailsModel.orderDetails?.id == null
            ? null
            : const OrderDetailsButtons(),
      );
    });
  }
}
