import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class OrderPaymentStatusChip extends StatelessWidget {
  final String status;
  final bool isCOD;
  const OrderPaymentStatusChip(
      {super.key, required this.status, required this.isCOD});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        color: isCOD ? null : status.getPaymentMutedStatusColor,
        borderColor: isCOD
            ? context.color.primaryBorderColor
            : status.getPaymentMutedStatusColor,
        radius: 4,
        child: Text(
          isCOD
              ? LocalKeys.cod
              : "${LocalKeys.payment}: ${status.getPaymentStatus}",
          style: context.bodySmall?.copyWith(
              color: isCOD
                  ? context.color.tertiaryContrastColo
                  : status.getPaymentPrimaryStatusColor),
        ));
  }
}
