import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:prohandy_client/views/job_list_view/components/job_tile.dart';
import 'package:prohandy_client/views/sub_order_view/components/suborder_service_tile.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../utils/components/alerts.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/navigation_pop_icon.dart';

class DeclineCompletionRequestView extends StatelessWidget {
  final SubOrder subOrder;
  const DeclineCompletionRequestView({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    final odm = OrderListViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          LocalKeys.declineRequest,
        ),
      ),
      body: SingleChildScrollView(
        child: Form(
          key: odm.formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Divider(
                height: 8,
                thickness: 8,
                color: context.color.backgroundColor,
              ),
              if (subOrder.service != null)
                SuborderServiceTile(
                  service: subOrder.service,
                ),
              if (subOrder.job != null)
                JobTile(
                  job: subOrder.job!,
                ),
              8.toHeight,
              FieldWithLabel(
                label: LocalKeys.reason,
                hintText: LocalKeys.enterOrderDeclineReason,
                controller: odm.declineReasonController,
                minLines: 4,
                validator: (val) {
                  if ((val ?? "").length < 3) {
                    return LocalKeys.enterAValidReason;
                  }
                  return null;
                },
              ).hp20
            ],
          ),
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: CustomButton(
            onPressed: () {
              if (odm.formKey.currentState?.validate() != true) return;
              Alerts().confirmationAlert(
                  context: context,
                  title: LocalKeys.areYouSure,
                  buttonText: LocalKeys.decline,
                  onConfirm: () async {
                    final od = Provider.of<OrderDetailsService>(context,
                        listen: false);
                    final result = await od.tryDecliningRequest(
                      orderId: subOrder.orderId,
                      subOrderId: subOrder.id,
                      declineReason: odm.declineReasonController.text,
                    );
                    if (result != true) return;
                    context.pop;
                    context.pop;
                  });
            },
            btText: LocalKeys.decline),
      ),
    );
  }
}
