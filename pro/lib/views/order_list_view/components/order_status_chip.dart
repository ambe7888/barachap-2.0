import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class OrderStatusChip extends StatelessWidget {
  final String status;
  const OrderStatusChip({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        color: status.getOrderMutedStatusColor,
        radius: 4,
        child: Text(
          status.getOrderStatus.capitalize,
          style: context.bodySmall
              ?.copyWith(color: status.getOrderPrimaryStatusColor),
        ));
  }
}
