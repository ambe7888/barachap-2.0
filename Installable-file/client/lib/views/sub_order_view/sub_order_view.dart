import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/sub_order_view/components/sub_order_note.dart';
import 'package:prohandy_client/views/sub_order_view/components/suborder_admin.dart';
import 'package:prohandy_client/views/sub_order_view/components/suborder_cost_info.dart';
import 'package:prohandy_client/views/sub_order_view/components/suborder_my_review.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../services/order_services/order_details_service.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../order_details_view/components/order_complete_requests.dart';
import '../order_details_view/components/order_refund_requests.dart';
import 'components/sub_order_staffs.dart';
import 'components/suborder_address.dart';
import 'components/suborder_buttons.dart';
import 'components/suborder_date_schedule.dart';
import 'components/suborder_job_tile.dart';
import 'components/suborder_provider.dart';
import 'components/suborder_providers_review.dart';
import 'components/suborder_service_tile.dart';

class SubOrderView extends StatelessWidget {
  final SubOrder subOrder;
  const SubOrderView({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return Consumer<OrderDetailsService>(
      builder: (context, od, child) {
        final so = od.orderDetailsModel.orderDetails!.subOrders!.firstWhere(
          (s) => s.id == subOrder.id,
        );
        return Scaffold(
          appBar: AppBar(
            leading: const NavigationPopIcon(),
            title: Text(so.service?.title ?? ""),
          ),
          body: CustomRefreshIndicator(
            onRefresh: () async {
              await Provider.of<OrderDetailsService>(
                context,
                listen: false,
              ).fetchOrderDetails(orderId: so.orderId);
            },
            child: Scrollbar(
              child: SingleChildScrollView(
                padding: 8.paddingV,
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    if (so.service != null)
                      SuborderServiceTile(service: so.service),
                    if (so.job != null) SuborderJobTile(job: so.job!),
                    if (so.provider != null) ...[
                      8.toHeight,
                      SuborderProvider(provider: so.provider!),
                    ],
                    if (so.service?.admin != null) ...[
                      8.toHeight,
                      SuborderAdmin(admin: so.service!.admin!),
                    ],
                    8.toHeight,
                    SuborderDateSchedule(subOrder: so),
                    if (so.subOrderLocations != null) ...[
                      8.toHeight,
                      SuborderAddress(address: so.subOrderLocations!),
                    ],
                    OrderRefundRequests(subOrder: so),
                    SuborderMyReview(so: so),
                    SuborderProvidersReview(so: so),
                    if (so.staff?.fullname != null) ...[
                      8.toHeight,
                      SubOrderStaffs(staff: so.staff!),
                    ],
                    if (so.orderNote?.isNotEmpty ?? false) ...[
                      8.toHeight,
                      SubOrderNote(note: so.orderNote!.trim()),
                    ],
                    OrderCompleteRequests(subOrder: so),
                    8.toHeight,
                    SuborderCostInfo(subOrder: so),
                  ],
                ),
              ),
            ),
          ),
          bottomNavigationBar: Builder(
            builder: (context) {
              return SuborderButtons(
                subOrder: subOrder,
                orderDetailsService: od,
              );
            },
          ),
        );
      },
    );
  }

  String primaryButtonText(completeRequestStatus) {
    switch (completeRequestStatus) {
      case "0":
        return LocalKeys.completeOrder;
      case "2":
        return LocalKeys.submitReview;
      default:
        return LocalKeys.na;
    }
  }
}
