import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../models/order_models/refund_details_model.dart';
import '../../../utils/components/info_tile.dart';

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
        ],
      ),
    );
  }
}
