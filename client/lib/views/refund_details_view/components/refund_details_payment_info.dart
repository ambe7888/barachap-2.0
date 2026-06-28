import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/order_models/refund_details_model.dart';
import '../../../utils/components/info_tile.dart';
import '../../../view_models/refund_list_view_model/refund_list_view_model.dart';
import '../payment_info_update_view.dart';

class RefundDetailsPaymentInfo extends StatelessWidget {
  final RefundDetails refundDetails;
  const RefundDetailsPaymentInfo({super.key, required this.refundDetails});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        spacing: 8,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ...(refundDetails.gatewayFields?.keys
                  .map(
                    (e) => InfoTile(
                      title: e.toString().replaceAll("_", " ").capitalize,
                      value: refundDetails.gatewayFields![e].toString(),
                    ),
                  )
                  .toList() ??
              []),
          if ((refundDetails.gatewayFields ?? {}).isEmpty)
            Text(LocalKeys.noPaymentInfoFound, style: context.titleSmall),
          if (refundDetails.status == "0")
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  RefundListViewModel.instance.selectedGateway.value = null;
                  context.toPage(
                    PaymentInfoUpdateView(
                      paymentGatewayId: refundDetails.gatewayId,
                    ),
                  );
                },
                child: Text(LocalKeys.updatePaymentInfo),
              ),
            ),
        ],
      ),
    );
  }
}
