import 'dart:io';

import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/services/home_services/unread_count_service.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../services/theme_service.dart';
import '../../../view_models/home_view_model/home_view_model.dart';
import '../../../view_models/landding_view_model/landding_view_model.dart';

class LandingNavBar extends StatelessWidget {
  const LandingNavBar({super.key});

  @override
  Widget build(BuildContext context) {
    final ov = LandingViewModel.instance;
    final ucs = UnreadCountService.instance;
    return Consumer<ThemeService>(
      builder: (context, ts, child) {
        return ValueListenableBuilder(
          valueListenable: ov.currentIndex,
          builder:
              (context, value, child) => Container(
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                ),
                height: 64 + (Platform.isIOS ? (context.paddingBottom / 2) : 0),
                padding: EdgeInsets.only(
                  top: 14,
                  bottom:
                      14 + (Platform.isIOS ? (context.paddingBottom / 2) : 0),
                ),
                child: FittedBox(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      navBarItem(
                        context,
                        LocalKeys.home,
                        SvgAssets.home,
                        0,
                        ov,
                      ),
                      navBarItem(
                        context,
                        LocalKeys.orders,
                        SvgAssets.list,
                        1,
                        ov,
                      ),
                      ValueListenableBuilder(
                        valueListenable: ucs.messageCount,
                        builder: (context, count, child) {
                          return navBarItem(
                            context,
                            LocalKeys.message,
                            SvgAssets.message,
                            2,
                            ov,
                            badgeCount: count,
                          );
                        },
                      ),
                      navBarItem(
                        context,
                        LocalKeys.jobs,
                        SvgAssets.jobs,
                        3,
                        ov,
                      ),
                      navBarItem(
                        context,
                        LocalKeys.profile,
                        SvgAssets.user,
                        4,
                        ov,
                      ),
                    ],
                  ),
                ),
              ),
        );
      },
    );
  }

  navBarItem(
    BuildContext context,
    String label,
    String iconNormal,
    int index,
    ov, {
    badgeCount = 0,
  }) {
    final selected = index == ov.currentIndex.value;
    return InkWell(
      onTap: () {
        HomeViewModel.instance.appBarSize.value = 0;
        ov.setContext(context);
        ov.setNavIndex(index);
      },
      child: SquircleContainer(
        height: 36,
        alignment: Alignment.center,
        margin: const EdgeInsets.symmetric(horizontal: 16),
        padding: EdgeInsets.symmetric(
          horizontal: selected ? (badgeCount > 0 ? 0 : 8) : 0,
          vertical: badgeCount > 0 ? 0 : 8,
        ),
        constraints: BoxConstraints(minWidth: selected ? 132 : 0.0),
        color: primaryColor,
        radius: selected ? 18 : null,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            SizedBox(
              width: (badgeCount > 0 && !selected) ? 44 : null,
              child: Align(
                alignment: Alignment.center,
                child: badges.Badge(
                  showBadge: (badgeCount > 0 && !selected),
                  position: badges.BadgePosition.topEnd(),
                  badgeContent: Text(
                    '$badgeCount',
                    style: const TextStyle().copyWith(
                      color: context.color.accentContrastColor,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  child: SizedBox(
                    child: iconNormal.toSVGSized(
                      20,
                      color:
                          selected
                              ? context.color.accentContrastColor
                              : context.color.secondaryContrastColor,
                    ),
                  ),
                ),
              ),
            ),
            if (selected) ...[
              8.toWidth,
              Text(
                label,
                style:
                    context.bodySmall
                        ?.copyWith(color: context.color.accentContrastColor)
                        .bold5,
              ),
            ],
          ],
        ),
      ),
    );
  }
}
