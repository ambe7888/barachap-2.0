import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../models/address_models/address_model.dart';

class AddressTile extends StatelessWidget {
  final Address address;
  const AddressTile({super.key, required this.address});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        children: [
          SvgAssets.addressHome
              .toSVGSized(24, color: context.color.tertiaryContrastColo),
          8.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  address.title ?? "---",
                  style: context.titleSmall?.bold,
                ),
                Text(
                  address.address ?? "---",
                  style: context.bodySmall,
                ),
              ],
            ),
          ),
          8.toWidth,
          SquircleContainer(
            padding: 6.paddingAll,
            borderColor: context.color.primaryBorderColor,
            radius: 8,
            child: SvgAssets.pencil
                .toSVGSized(24, color: context.color.tertiaryContrastColo),
          ),
        ],
      ),
    );
  }
}
