import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/services/withdraw_services/withdraw_history_service.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';
import 'package:prohand/view_models/withdraw_view_model/withdraw_view_model.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../request_withdraw_view/components/balance_box.dart';
import 'components/withdraw_history_card.dart';
import 'components/withdraw_request_button.dart';

class WithdrawView extends StatelessWidget {
  const WithdrawView({super.key});

  @override
  Widget build(BuildContext context) {
    final wlProvider =
        Provider.of<WithdrawHistoryService>(context, listen: false);
    final whm = WithdrawViewModel.instance;
    whm.scrollController.addListener(() {
      whm.tryToLoadMore(context);
    });
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.withdraw),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          Provider.of<DashboardInfoService>(context, listen: false)
              .fetchDashboardInfo();
          await wlProvider.fetchWithdrawHistory();
        },
        child: CustomFutureWidget(
          function: wlProvider.shouldAutoFetch
              ? wlProvider.fetchWithdrawHistory()
              : null,
          shimmer: const CustomPreloader(),
          child:
              Consumer<WithdrawHistoryService>(builder: (context, wl, child) {
            return Scrollbar(
                child: CustomScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              controller: whm.scrollController,
              slivers: [
                Divider(
                  color: context.color.backgroundColor,
                  height: 8,
                  thickness: 8,
                ).toSliver,
                12.toHeight.toSliver,
                Consumer<DashboardInfoService>(builder: (context, di, child) {
                  return BalanceBox(
                      balance: (di.dashboardInfoModel?.remainingBalance ?? 0));
                }).toSliver,
                Divider(
                  color: context.color.backgroundColor,
                  height: 8,
                  thickness: 8,
                ).toSliver,
                const SizedBox(height: 12).toSliver,
                (wl.withdrawHistory.histories ?? []).isEmpty
                    ? SizedBox(
                        height: 400,
                        child: EmptyWidget(
                          title: LocalKeys.noHistoryFound,
                          physics: const NeverScrollableScrollPhysics(),
                        ),
                      ).toSliver
                    : SliverList.separated(
                        itemBuilder: (context, index) {
                          final history = wl.withdrawHistory.histories![index];
                          return WithdrawHistoryCard(
                            history: history,
                            path: "",
                          );
                        },
                        separatorBuilder: (context, index) => 12.toHeight,
                        itemCount: wl.withdrawHistory.histories?.length ?? 0,
                      ),
                12.toHeight.toSliver,
                if (wl.nextPage != null && !wl.nexLoadingFailed)
                  ScrollPreloader(loading: wl.nextPageLoading).toSliver,
              ],
            ));
          }),
        ),
      ),
      bottomNavigationBar: const WithdrawRequestButton(),
    );
  }
}
