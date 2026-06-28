import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';

import '../../../../utils/components/empty_element.dart';
import '../../../../utils/components/field_label.dart';
import '../../../helper/local_keys.g.dart';
import '../../../models/order_models/refund_details_model.dart';

class RefundDetailsReason extends StatelessWidget {
  final RefundDetails refundDetails;
  const RefundDetailsReason({super.key, required this.refundDetails});

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
          FieldLabel(label: LocalKeys.reason),
          (refundDetails.cancelReason ?? "").isEmpty
              ? Center(child: EmptyElement(text: LocalKeys.notSpecified))
              : Text(
                refundDetails.cancelReason ?? "",
                style: context.bodySmall,
              ),
        ],
      ),
    );
  }
}
