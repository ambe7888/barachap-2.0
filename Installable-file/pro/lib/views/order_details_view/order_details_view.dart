import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/order_details_view/components/order_clients_review.dart';
import 'package:prohand/views/order_details_view/components/order_details_buttons.dart';
import 'package:provider/provider.dart';

import '../../services/order_services/order_details_service.dart';
import '../../utils/components/empty_widget.dart';
import 'components/order_complete_requests.dart';
import 'components/order_date_schedule.dart';
import 'components/order_details_address.dart';
import 'components/order_details_cancellation_policy.dart';
import 'components/order_details_client_tile.dart';
import 'components/order_details_earning.dart';
import 'components/order_details_job_tile.dart';
import 'components/order_details_note.dart';
import 'components/order_details_phone.dart';
import 'components/order_details_service_tile.dart';
import 'components/order_details_skeleton.dart';
import 'components/order_details_staffs.dart';
import 'components/order_my_review.dart';
import 'components/order_refund_request.dart';

class OrderDetailsView extends StatelessWidget {
  static const routeName = "order_details_view";
  final dynamic orderId;
  const OrderDetailsView({super.key, this.orderId});

  @override
  Widget build(BuildContext context) {
    final id = orderId ?? context.arguments;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          "#$id",
          style: context.titleLarge?.bold.copyWith(color: primaryColor),
        ),
      ),
      body: Consumer<OrderDetailsService>(builder: (context, od, child) {
        final orderDetails = od.orderDetailsModel.orderDetails;
        return CustomRefreshIndicator(
          onRefresh: () async {
            await od.fetchOrderDetails(orderId: id);
          },
          child: CustomFutureWidget(
            function: od.shouldAutoFetch(id)
                ? od.fetchOrderDetails(orderId: id)
                : null,
            shimmer: const OrderDetailsSkeleton(),
            child: od.orderDetailsModel.orderDetails == null
                ? EmptyWidget(
                    title: LocalKeys.orderNotFound,
                    margin: EdgeInsets.only(top: 8),
                  )
                : Scrollbar(
                    child: Column(
                      children: [
                        Expanded(
                          child: CustomScrollView(
                            slivers: [
                              Column(
                                children: [
                                  Padding(
                                    padding: 8.paddingV,
                                    child: orderDetails == null
                                        ? EmptyWidget(
                                            title:
                                                LocalKeys.orderDetailsNotFound)
                                        : Column(
                                            children: [
                                              if (orderDetails.service != null)
                                                OrderDetailsServiceTile(
                                                    orderDetails: orderDetails),
                                              if (orderDetails.job != null)
                                                OrderDetailsJobTile(
                                                    job: orderDetails.job!),
                                              8.toHeight,
                                              const OrderDetailsClientTile(),
                                              8.toHeight,
                                              OrderDateSchedule(
                                                date: orderDetails.date,
                                                schedule: orderDetails.schedule,
                                              ),
                                              8.toHeight,
                                              OrderDetailsAddress(
                                                  address: orderDetails
                                                      .subOrderLocations),
                                              8.toHeight,
                                              OrderDetailsPhone(
                                                  phone: orderDetails
                                                      .subOrderLocations
                                                      ?.phone),
                                              OrderClientsReview(
                                                orderDetails: orderDetails,
                                              ),
                                              OrderMyReview(
                                                orderDetails: orderDetails,
                                              ),
                                              if (orderDetails
                                                      .orderNote?.isNotEmpty ??
                                                  false) ...[
                                                8.toHeight,
                                                OrderDetailsNote(
                                                    note:
                                                        orderDetails.orderNote)
                                              ],
                                              8.toHeight,
                                              OrderDetailsEarning(
                                                  orderDetails: orderDetails),
                                              if (orderDetails.staff !=
                                                  null) ...[
                                                8.toHeight,
                                                OrderDetailsStaffs(
                                                    staff: orderDetails.staff)
                                              ],
                                              8.toHeight,
                                              OrderRefundRequest(
                                                  refundDetails: orderDetails
                                                      .refundDetails),
                                              const OrderCompleteRequests(),
                                              if (1 == 2) ...[
                                                const OrderDetailsCancellationPolicy()
                                              ],
                                            ],
                                          ),
                                  ),
                                ],
                              ).toSliver,
                            ],
                          ),
                        ),
                        if (orderDetails != null)
                          OrderDetailsButtons(orderDetails: orderDetails)
                      ],
                    ),
                  ),
          ),
        );
      }),
    );
  }
}
