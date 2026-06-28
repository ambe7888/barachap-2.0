import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../services/order_services/refund_manage_service.dart';
import '../../services/order_services/refund_settings_service.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/custom_dropdown.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/field_label.dart';
import '../../utils/components/field_with_label.dart';
import '../../utils/components/navigation_pop_icon.dart';
import '../../view_models/refund_list_view_model/refund_list_view_model.dart';

class PaymentInfoUpdateView extends StatelessWidget {
  final paymentGatewayId;
  const PaymentInfoUpdateView({super.key, this.paymentGatewayId});

  @override
  Widget build(BuildContext context) {
    final rlm = RefundListViewModel.instance;
    final rmProvider = Provider.of<RefundManageService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        title: Text(LocalKeys.updatePaymentInfo),
        leading: NavigationPopIcon(),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(vertical: 8),
        child: Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          color: context.color.accentContrastColor,
          child: Form(
            key: rlm.formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FieldLabel(label: LocalKeys.paymentMethods),
                Consumer<RefundSettingsService>(
                  builder: (context, rs, child) {
                    return CustomFutureWidget(
                      function:
                          rs.shouldAutoFetch
                              ? rs.fetchWithdrawSettings()
                              : null,
                      shimmer: const CustomPreloader(),
                      child: Builder(
                        builder: (context) {
                          if (paymentGatewayId != null) {
                            final pm = rs.withdrawSettingsModel.withdrawGateways
                                .firstWhere(
                                  (element) =>
                                      element.id?.toString() ==
                                      paymentGatewayId.toString(),
                                );
                            rlm.setSelectedGateway(rs, pm.name);
                            for (
                              var i = 0;
                              i <
                                  (rmProvider
                                              .refundDetailsModel
                                              .refundDetails
                                              ?.gatewayFields
                                              ?.values ??
                                          [])
                                      .length;
                              i++
                            ) {
                              rlm.inputFieldControllers[i].text =
                                  rmProvider
                                      .refundDetailsModel
                                      .refundDetails
                                      ?.gatewayFields
                                      ?.values
                                      .toList()[i] ??
                                  "";
                            }
                          }
                          return ValueListenableBuilder(
                            valueListenable: rlm.selectedGateway,
                            builder: (context, gateway, child) {
                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  CustomDropdown(
                                    LocalKeys.selectAPaymentMethod,
                                    rs.withdrawSettingsModel.withdrawGateways
                                        .map((e) => e.name)
                                        .toList(),
                                    (value) {
                                      rlm.setSelectedGateway(rs, value);
                                    },
                                    value: gateway?.name,
                                  ),
                                  16.toHeight,
                                  if (gateway != null)
                                    ...Iterable.generate(
                                      gateway.field.length,
                                    ).map((e) {
                                      return FieldWithLabel(
                                            label: gateway.field[e],
                                            hintText:
                                                gateway.field[e]
                                                    ?.toString()
                                                    .capitalize ??
                                                "",
                                            isRequired: true,
                                            controller:
                                                rlm.inputFieldControllers[e],
                                            validator: (value) {
                                              if (value!.isEmpty) {
                                                return gateway.field[e]
                                                    ?.toString()
                                                    .capitalize;
                                              }
                                              return null;
                                            },
                                          )
                                          .animate(
                                            delay:
                                                (e * 100 as int).milliseconds,
                                          )
                                          .slideY()
                                          .fadeIn();
                                    }),
                                ],
                              );
                            },
                          );
                        },
                      ),
                    );
                  },
                ),
                ValueListenableBuilder(
                  valueListenable: rlm.isLoading,
                  builder:
                      (context, loading, child) => CustomButton(
                        onPressed: () async {
                          context.unFocus;
                          if (rlm.selectedGateway.value == null) {
                            LocalKeys.selectAPaymentMethod.showToast();
                            return;
                          }
                          if (!(rlm.formKey.currentState?.validate() ??
                              false)) {
                            debugPrint(
                              (rlm.formKey.currentState?.validate()).toString(),
                            );
                            return;
                          }
                          rlm.isLoading.value = true;
                          final result =
                              await Provider.of<RefundManageService>(
                                context,
                                listen: false,
                              ).tryUpdatingPaymentInfo();
                          if (result) {
                            context.pop;
                          }
                          rlm.isLoading.value = false;
                        },
                        isLoading: loading,
                        btText: LocalKeys.update0,
                      ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
