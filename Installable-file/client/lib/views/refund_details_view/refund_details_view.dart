import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../helper/extension/int_extension.dart';
import '../../helper/extension/widget_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../services/order_services/refund_manage_service.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../../utils/components/empty_widget.dart';
import '../../utils/components/navigation_pop_icon.dart';
import '../../view_models/refund_list_view_model/refund_list_view_model.dart';
import 'components/refund_details_basic_info.dart';
import 'components/refund_details_payment_info.dart';
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
            function:
                rmProvider.shouldAutoFetch(refundId)
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
                      RefundDetailsPaymentInfo(
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
    );
  }
}
