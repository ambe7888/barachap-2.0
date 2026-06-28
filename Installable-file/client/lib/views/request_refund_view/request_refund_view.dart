import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:provider/provider.dart';

import '../../helper/extension/context_extension.dart';
import '../../helper/extension/int_extension.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../services/dynamics/cancellation_policy_service.dart';
import '../../services/order_services/order_details_service.dart';
import '../../services/order_services/refund_settings_service.dart';
import '../../utils/components/alerts.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/custom_dropdown.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/field_label.dart';
import '../../utils/components/field_with_label.dart';
import '../../utils/components/navigation_pop_icon.dart';
import '../../view_models/order_details_view_model/order_details_view_model.dart';

class RequestRefundView extends StatelessWidget {
  final dynamic subOrderId;
  const RequestRefundView({super.key, this.subOrderId});

  @override
  Widget build(BuildContext context) {
    final odm = OrderDetailsViewModel.instance;
    final cps = CancellationPolicyService.instance;
    return Scaffold(
      appBar: AppBar(
        title: Text(LocalKeys.requestRefund),
        leading: NavigationPopIcon(),
      ),
      body: SingleChildScrollView(
        child: Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          margin: const EdgeInsets.only(top: 8),
          color: context.color.accentContrastColor,
          child: Form(
            key: odm.formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FieldWithLabel(
                  label: LocalKeys.reason,
                  minLines: 3,
                  maxLines: 6,
                  controller: odm.reasonController,
                  textInputAction: TextInputAction.newline,
                  hintText: LocalKeys.bookingNoteExmp,
                ),
                FieldLabel(label: LocalKeys.paymentMethods, isRequired: true),
                Consumer<RefundSettingsService>(
                  builder: (context, rs, child) {
                    return CustomFutureWidget(
                      function:
                          rs.shouldAutoFetch
                              ? rs.fetchWithdrawSettings()
                              : null,
                      shimmer: const CustomPreloader(),
                      child: ValueListenableBuilder(
                        valueListenable: odm.selectedGateway,
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
                                  odm.setSelectedGateway(rs, value);
                                },
                                value: gateway?.name,
                              ),
                              16.toHeight,
                              if (gateway != null)
                                ...Iterable.generate(gateway.field.length).map((
                                  e,
                                ) {
                                  return FieldWithLabel(
                                        label: gateway.field[e],
                                        hintText:
                                            gateway.field[e]
                                                ?.toString()
                                                .capitalize ??
                                            "",
                                        isRequired: true,
                                        controller:
                                            odm.inputFieldControllers[e],
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
                                        delay: (e * 100 as int).milliseconds,
                                      )
                                      .slideY()
                                      .fadeIn();
                                }),
                            ],
                          );
                        },
                      ),
                    );
                  },
                ),
              ],
            ),
          ),
        ),
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border: Border(
            top: BorderSide(color: context.color.primaryBorderColor),
          ),
        ),
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        child: CustomButton(
          onPressed: () {
            if (odm.selectedGateway.value == null) {
              LocalKeys.selectAPaymentMethod.showToast();
              return;
            }
            if (!(odm.formKey.currentState?.validate() ?? false)) {
              debugPrint((odm.formKey.currentState?.validate()).toString());
              return;
            }
            Alerts().confirmationAlert(
              context: context,
              title: LocalKeys.areYouSure,
              onConfirm: () async {
                final result = await Provider.of<OrderDetailsService>(
                  context,
                  listen: false,
                ).tryRefundRequest(subOrderId: subOrderId);
                if (result) {
                  context.pop;
                }
                context.pop;
              },
            );
            context.unFocus;
          },
          btText: LocalKeys.sendRequest,
        ),
      ),
    );
  }
}
