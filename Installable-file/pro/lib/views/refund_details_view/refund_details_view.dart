import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/order_services/order_details_service.dart';
import 'package:provider/provider.dart';

import '../../helper/extension/int_extension.dart';
import '../../helper/extension/widget_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../services/order_services/refund_manage_service.dart';
import '../../utils/components/alerts.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../../utils/components/empty_widget.dart';
import '../../utils/components/navigation_pop_icon.dart';
import '../../view_models/refund_list_view_model/refund_list_view_model.dart';
import 'components/refund_details_basic_info.dart';
import 'components/refund_details_reason.dart';

class RefundDetailsView extends StatelessWidget {
  final dynamic refundId;
  const RefundDetailsView({super.key, required this.refundId});

  @override
  Widget build(BuildContext context) {
    final rmProvider = Provider.of<RefundManageService>(context, listen: false);
    final rlm = RefundListViewModel.instance;
    return Scaffold(
      appBar: AppBar(leading: NavigationPopIcon()),
      body: CustomRefreshIndicator(
        refreshKey: rlm.refreshKey,
        onRefresh: () async {
          await rmProvider.fetchRefundDetails(id: refundId);
        },
        child: Scrollbar(
          controller: rlm.dScrollController,
          child: CustomFutureWidget(
            function: rmProvider.shouldAutoFetch(refundId)
                ? rmProvider.fetchRefundDetails(id: refundId)
                : null,
            shimmer: const CustomPreloader(),
            child: Consumer<RefundManageService>(
              builder: (context, rm, child) {
                if (rm.refundDetailsModel.refundDetails == null) {
                  return EmptyWidget(title: LocalKeys.somethingWentWrong);
                } else {
                  final refundDetails = rm.refundDetailsModel.refundDetails!;
                  return CustomScrollView(
                    controller: rlm.dScrollController,
                    physics: const AlwaysScrollableScrollPhysics(),
                    slivers: [
                      8.toHeight.toSliver,
                      RefundDetailsBasicInfo(
                        refundDetails: refundDetails,
                      ).toSliver,
                      8.toHeight.toSliver,
                      RefundDetailsReason(
                        refundDetails: refundDetails,
                      ).toSliver,
                    ],
                  );
                }
              },
            ),
          ),
        ),
      ),
      bottomNavigationBar:
          Consumer<RefundManageService>(builder: (context, rm, child) {
        final od = Provider.of<OrderDetailsService>(context, listen: false);
        return rm.refundDetailsModel.refundDetails?.status.toString() != "0"
            ? SizedBox()
            : Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                  border: Border(
                    top: BorderSide(
                      color: context.color.primaryBorderColor,
                      width: 1,
                    ),
                  ),
                ),
                child: Row(
                  children: [
                    Expanded(
                      flex: 1,
                      child: OutlinedButton.icon(
                        onPressed: () async {
                          Alerts().confirmationAlert(
                              context: context,
                              title: LocalKeys.declineRequest,
                              buttonColor: context.color.primaryWarningColor,
                              buttonText: LocalKeys.decline,
                              onConfirm: () async {
                                final result = await od.tryDeclineRefund(
                                  id: refundId,
                                  oID: rm.refundDetailsModel.refundDetails
                                      ?.suborderId,
                                );
                                if (result == true) {
                                  rlm.refreshKey.currentState?.show();
                                }
                                context.pop();
                              });
                        },
                        icon: const Icon(Icons.close),
                        label: Text(LocalKeys.decline),
                      ),
                    ),
                    12.toWidth,
                    Expanded(
                      flex: 1,
                      child: ElevatedButton.icon(
                        onPressed: () async {
                          Alerts().confirmationAlert(
                              context: context,
                              title: LocalKeys.acceptRequest,
                              buttonText: LocalKeys.accept,
                              onConfirm: () async {
                                final result = await od.tryAcceptRefund(
                                  id: refundId,
                                  oID: rm.refundDetailsModel.refundDetails
                                      ?.suborderId,
                                );
                                if (result == true) {
                                  rlm.refreshKey.currentState?.show();
                                }
                                context.pop();
                              });
                        },
                        icon: const Icon(Icons.check),
                        label: Text(LocalKeys.accept),
                      ),
                    ),
                  ],
                ));
      }),
    );
  }
}
