import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../services/theme_service.dart';
import '../../../view_models/home_view_model/unread_count_service.dart';
import '../../../view_models/landding_view_model/landding_view_model.dart';

class LandingNavBar extends StatelessWidget {
  const LandingNavBar({super.key});

  @override
  Widget build(BuildContext context) {
    final ov = LandingViewModel.instance;
    final ucs = UnreadCountService.instance;
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return ValueListenableBuilder(
          valueListenable: ov.currentIndex,
          builder: (context, value, child) => Container(
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                ),
                height: 64,
                padding: const EdgeInsets.symmetric(vertical: 14),
                child: FittedBox(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      navBarItem(
                          context, LocalKeys.home, SvgAssets.home, 0, ov),
                      navBarItem(
                          context, LocalKeys.orders, SvgAssets.list, 1, ov),
                      navBarItem(
                          context, LocalKeys.services, SvgAssets.tool, 2, ov),
                      navBarItem(
                        context,
                        LocalKeys.jobs,
                        SvgAssets.jobs,
                        3,
                        ov,
                      ),
                      ValueListenableBuilder(
                        valueListenable: ucs.messageCount,
                        builder: (context, count, child) {
                          return navBarItem(context, LocalKeys.message,
                              SvgAssets.message, 4, ov,
                              badgeCount: count);
                        },
                      ),
                    ],
                  ),
                ),
              ));
    });
  }

  navBarItem(
      BuildContext context, String label, String iconNormal, int index, ov,
      {badgeCount = 0}) {
    final selected = index == ov.currentIndex.value;
    return InkWell(
      onTap: () {
        ov.setNavIndex(index);
      },
      child: SquircleContainer(
        height: 36,
        alignment: Alignment.center,
        margin: const EdgeInsets.symmetric(horizontal: 16),
        padding: EdgeInsets.symmetric(
            horizontal: selected ? 8 : 0, vertical: badgeCount > 0 ? 0 : 8),
        constraints: BoxConstraints(minWidth: selected ? 132 : 0.0),
        color: primaryColor,
        radius: selected ? 18 : null,
        child: Row(mainAxisAlignment: MainAxisAlignment.center, children: [
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
                      fontWeight: FontWeight.w600),
                ),
                child: iconNormal.toSVGSized(
                  24,
                  color: selected
                      ? context.color.accentContrastColor
                      : context.color.secondaryContrastColor,
                ),
              ),
            ),
          ),
          if (selected) 8.toWidth,
          if (selected)
            Text(
              label,
              style: context.bodySmall
                  ?.copyWith(color: context.color.accentContrastColor)
                  .bold5,
            )
        ]),
      ),
    );
  }
}
