import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_network_image.dart';

class CartTile extends StatelessWidget {
  const CartTile({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const CustomNetworkImage(
            height: 40,
            width: 40,
            radius: 10,
            fit: BoxFit.cover,
            imageUrl:
                "https://i.postimg.cc/tCY4JbQq/order-attachment-1716468898.jpg",
          ),
          8.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Home Cleaning Services at Miami, FL at Miami, FL ",
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleSmall?.bold,
                ),
              ],
            ),
          ),
          8.toWidth,
          TextButton.icon(
            onPressed: () {},
            label: Text(283.98.cur),
            icon: Transform.rotate(
              angle: context.dProvider.textDirectionRight ? pi : 0,
              child: SvgAssets.chevron.toSVGSized(
                20,
                color: primaryColor,
              ),
            ),
            iconAlignment: IconAlignment.end,
          )
        ],
      ),
    );
  }
}
