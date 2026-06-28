import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../models/order_models/refund_details_model.dart';
import '../../../utils/components/info_tile.dart';

class RefundDetailsBasicInfo extends StatelessWidget {
  final RefundDetails refundDetails;
  const RefundDetailsBasicInfo({super.key, required this.refundDetails});

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
          InfoTile(title: LocalKeys.id, value: refundDetails.id.toString()),
          if (refundDetails.order?.id != null)
            InfoTile(
              title: LocalKeys.orderId,
              value: refundDetails.suborderId.toString(),
            ),
          if (refundDetails.gatewayName != null)
            InfoTile(
              title: LocalKeys.paymentGateway,
              value: refundDetails.gatewayName.toString(),
            ),
          InfoTile(
            title: LocalKeys.refundAmount,
            value: refundDetails.amount.cur,
            valueColor: primaryColor,
          ),
          InfoTile(
            title: LocalKeys.status0,
            value: refundDetails.status.getRefundStatus.capitalize,
            valueColor: refundDetails.status.getRefundPrimaryStatusColor,
          ),
        ],
      ),
    );
  }
}
