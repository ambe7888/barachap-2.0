import 'package:badges/badges.dart' as badge;
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';

import '../../../utils/components/custom_network_image.dart';

class SelectableStaff extends StatelessWidget {
  final bool isSelected;
  const SelectableStaff({super.key, required this.isSelected});

  @override
  Widget build(BuildContext context) {
    return badge.Badge(
      showBadge: isSelected,
      badgeContent: SvgAssets.doneFilled.toSVGSized(24, color: primaryColor),
      badgeStyle:
          badge.BadgeStyle(badgeColor: context.color.accentContrastColor),
      child: const CustomNetworkImage(
        height: 44,
        width: 44,
        radius: 22,
        fit: BoxFit.cover,
        imageUrl:
            "https://i.postimg.cc/tCY4JbQq/order-attachment-1716468898.jpg",
      ),
    );
  }
}
