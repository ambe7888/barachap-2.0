import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/utils/components/currency_icon.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/request_withdraw_view_model/request_withdraw_view_model.dart';
import 'package:prohand/views/request_withdraw_view/components/balance_box.dart';
import 'package:provider/provider.dart';

import '../../services/request_withdraw_service.dart';
import '../../utils/components/custom_dropdown.dart';
import '../../utils/components/field_label.dart';
import 'components/send_withdraw_request.dart';

class RequestWithdrawView extends StatelessWidget {
  const RequestWithdrawView({super.key});

  @override
  Widget build(BuildContext context) {
    final rwm = RequestWithdrawViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => RequestWithdrawService(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.requestWithdraw),
        ),
        body: Consumer<RequestWithdrawService>(builder: (context, rw, child) {
          return CustomRefreshIndicator(
            onRefresh: () async {
              await Provider.of<DashboardInfoService>(context, listen: false)
                  .fetchDashboardInfo();
              await rw.fetchWithdrawSettings();
            },
            child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Form(
                  key: rwm.formKey,
                  child: Column(
                    children: [
                      Divider(
                        height: 8,
                        thickness: 8,
                        color: context.color.backgroundColor,
                      ),
                      12.toHeight,
                      Consumer<DashboardInfoService>(
                          builder: (context, di, child) {
                        return BalanceBox(
                            balance:
                                (di.dashboardInfoModel?.remainingBalance ?? 0));
                      }),
                      Divider(
                        height: 8,
                        thickness: 8,
                        color: context.color.backgroundColor,
                      ),
                      12.toHeight,
                      Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Center(
                              child: Text(
                                LocalKeys.withdrawTo,
                                style: context.titleLarge?.bold6,
                              ),
                            ),
                            FieldWithLabel(
                              label: LocalKeys.amount,
                              hintText: LocalKeys.enterAmount,
                              isRequired: true,
                              controller: rwm.amountController,
                              prefixIcon: const Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  CurrencyIcon(
                                    height: 32,
                                  ),
                                ],
                              ),
                            ),
                            FieldLabel(
                              label: LocalKeys.withdrawMethod,
                              isRequired: true,
                            ),
                            CustomFutureWidget(
                              function: rw.shouldAutoFetch
                                  ? rw.fetchWithdrawSettings()
                                  : null,
                              shimmer: const CustomPreloader(),
                              child: ValueListenableBuilder(
                                valueListenable: rwm.selectedGateway,
                                builder: (context, gateway, child) {
                                  return Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      CustomDropdown(
                                        LocalKeys.selectAPaymentMethod,
                                        rw.withdrawSettingsModel
                                            .withdrawGateways
                                            .map((e) => e.name)
                                            .toList(),
                                        (value) {
                                          rwm.setSelectedGateway(rw, value);
                                        },
                                        value: gateway?.name,
                                      ),
                                      if (gateway != null)
                                        ...Iterable.generate(
                                                gateway.field.length)
                                            .map(
                                          (e) {
                                            return FieldWithLabel(
                                              label: gateway.field[e].tr(),
                                              hintText: gateway.field[e]
                                                      ?.toString()
                                                      .tr()
                                                      .capitalize ??
                                                  "",
                                              isRequired: true,
                                              controller:
                                                  rwm.inputFieldControllers[e],
                                              validator: (value) {
                                                if (value!.isEmpty) {
                                                  return gateway
                                                      .field[e].tr().capitalize;
                                                }
                                                return null;
                                              },
                                            )
                                                .animate(
                                                    delay: (e * 100 as int)
                                                        .milliseconds)
                                                .slideY()
                                                .fadeIn();
                                          },
                                        )
                                    ],
                                  );
                                },
                              ),
                            ),
                          ]).hp20,
                    ],
                  ),
                )),
          );
        }),
        bottomNavigationBar: const SendWithdrawRequest(),
      ),
    );
  }
}
