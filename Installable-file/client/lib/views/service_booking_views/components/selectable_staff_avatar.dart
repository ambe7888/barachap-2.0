import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';

import '../../../models/service/service_details_model.dart';

class SelectableStaffAvatar extends StatelessWidget {
  final id;
  final ValueNotifier<Staff?> valueListenable;
  final String? imageUrl;
  final String? name;
  final void Function() onSelect;
  const SelectableStaffAvatar(
      {super.key,
      this.id,
      required this.valueListenable,
      this.imageUrl,
      this.name,
      required this.onSelect});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onSelect,
      child: ValueListenableBuilder(
        valueListenable: valueListenable,
        builder: (context, value, child) {
          return badges.Badge(
            showBadge: value?.id.toString() == id.toString(),
            badgeContent:
                SvgAssets.doneFilled.toSVGSized(26, color: primaryColor),
            badgeStyle: badges.BadgeStyle(
              padding: EdgeInsets.all(2),
              badgeColor: Colors.white,
            ),
            child: CustomNetworkImage(
              height: 54,
              width: 54,
              radius: 32,
              fit: BoxFit.cover,
              name: name,
              imageUrl: imageUrl,
            ),
          );
        },
      ),
    );
  }
}
