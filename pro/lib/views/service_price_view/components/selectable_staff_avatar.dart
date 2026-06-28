import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/utils/components/custom_network_image.dart';

class SelectableStaffAvatar extends StatelessWidget {
  final id;
  final bool isSelected;
  final String? imageUrl;
  final String? name;
  final void Function() onSelect;
  const SelectableStaffAvatar(
      {super.key,
      this.id,
      required this.isSelected,
      this.imageUrl,
      this.name,
      required this.onSelect});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onSelect,
      child: badges.Badge(
        showBadge: isSelected,
        badgeContent: SvgAssets.doneFilled.toSVGSized(26, color: primaryColor),
        badgeStyle: badges.BadgeStyle(
          padding: EdgeInsets.all(2),
          badgeColor: Colors.white,
        ),
        child: CustomNetworkImage(
          height: 52,
          width: 52,
          radius: 32,
          fit: BoxFit.cover,
          name: name,
          imageUrl: imageUrl,
        ),
      ),
    );
  }
}
