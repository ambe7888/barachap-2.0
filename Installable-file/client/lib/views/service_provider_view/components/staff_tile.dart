import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../../utils/components/custom_network_image.dart';

class StaffTile extends StatelessWidget {
  final id;
  final String name;
  final String? imageUrl;
  final DateTime? createdAt;
  const StaffTile({
    super.key,
    this.id,
    required this.name,
    this.createdAt,
    this.imageUrl,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
      child: Row(
        children: [
          CustomNetworkImage(
            height: 40,
            width: 40,
            radius: 20,
            fit: BoxFit.cover,
            name: name,
            imageUrl: imageUrl,
            userPreloader: true,
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  name,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleMedium?.bold,
                ),
                if (createdAt != null) ...[
                  4.toHeight,
                  Text(
                    "${LocalKeys.added} ${DateFormat("dd MMM yyy", dProvider.languageSlug).format(createdAt!)}",
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: context.bodySmall?.copyWith(
                      color: context.color.tertiaryContrastColo,
                    ),
                  )
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }
}
