import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/address_models/address_model.dart';

class SummaryPhone extends StatelessWidget {
  final Address address;
  const SummaryPhone({super.key, required this.address});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.phone,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                6.toHeight,
                Text(
                  address.phone,
                  style: context.titleSmall?.bold,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
