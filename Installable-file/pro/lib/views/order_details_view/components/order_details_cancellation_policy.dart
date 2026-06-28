import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';

class OrderDetailsCancellationPolicy extends StatelessWidget {
  const OrderDetailsCancellationPolicy({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CircleAvatar(
                radius: 26,
                backgroundColor: context.color.mutedWarningColor,
                child: SvgAssets.verified
                    .toSVGSized(24, color: context.color.primaryWarningColor),
              ),
              12.toWidth,
              Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        LocalKeys.cancellationPolicy,
                        style: context.titleMedium?.bold,
                      ),
                      4.toHeight,
                      Text(
                        "You'll receive a 15% fee minus a cancellation charge if the service is cancelled by the customer 8 hours before the scheduled time.",
                        style: context.bodySmall,
                      ),
                    ],
                  ))
            ],
          )
        ],
      ),
    );
  }
}
