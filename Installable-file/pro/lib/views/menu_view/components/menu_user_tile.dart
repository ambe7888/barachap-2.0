import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/custom_network_image.dart';
import 'package:prohand/views/personal_information_view/personal_information_view.dart';
import 'package:provider/provider.dart';

class MenuUserTile extends StatelessWidget {
  final ThemeService ts;
  const MenuUserTile({super.key, required this.ts});

  @override
  Widget build(BuildContext context) {
    final piProvider = Provider.of<ProfileInfoService>(context, listen: false);
    return GestureDetector(
      onTap: () {
        context.toPage(const PersonalInformationView());
      },
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: ts.selectedTheme.accentContrastColor,
        child: Row(
          children: [
            CustomNetworkImage(
              height: 52,
              width: 52,
              radius: 26,
              imageUrl: piProvider.profileInfoModel.userDetails?.image,
              name: piProvider.profileInfoModel.userDetails?.firstName,
              fit: BoxFit.cover,
            ),
            8.toWidth,
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "${piProvider.profileInfoModel.userDetails?.firstName ?? ""} ${piProvider.profileInfoModel.userDetails?.lastName ?? ""}",
                    style: context.titleLarge?.bold,
                  ),
                  4.toHeight,
                  Text(
                    piProvider.profileInfoModel.userDetails?.email ?? "",
                    style: context.bodyMedium,
                  ),
                ],
              ),
            ),
            Transform.rotate(
              angle: context.dProvider.textDirectionRight ? pi : 0,
              child: SvgAssets.chevron.toSVGSized(
                20,
                color: ts.selectedTheme.secondaryContrastColor,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
