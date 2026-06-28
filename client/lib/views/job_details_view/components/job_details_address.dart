import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';

import '../../../helper/local_keys.g.dart';

class JobDetailsAddress extends StatelessWidget {
  final Address? address;
  const JobDetailsAddress({
    super.key,
    required this.address,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: context.color.primaryBorderColor.withOpacity(0.6),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.where,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.bodySmall
                ?.copyWith(color: context.color.tertiaryContrastColo),
          ),
          6.toHeight,
          Text(
            address?.address ?? "---",
            style: context.titleSmall?.bold,
          ),
        ],
      ),
    );
  }
}
