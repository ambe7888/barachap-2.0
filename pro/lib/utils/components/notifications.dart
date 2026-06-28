import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '../../helper/svg_assets.dart';
import '../../services/theme_service.dart';
import '../../view_models/home_view_model/unread_count_service.dart';
import '../../views/notification_list_view/notification_list_view.dart';

class Notifications extends StatelessWidget {
  const Notifications({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final ucs = UnreadCountService.instance;
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return GestureDetector(
        onTap: () {
          context.toPage(const NotificationListView(), then: (_) {
            UnreadCountService.instance.fetchUnreadCounts();
          });
        },
        child: ValueListenableBuilder(
          valueListenable: ucs.notificationCount,
          builder: (context, count, child) => Align(
            alignment: Alignment.center,
            child: badges.Badge(
              showBadge: count > 0,
              badgeContent: ConstrainedBox(
                constraints: const BoxConstraints(minWidth: 12),
                child: Text(
                  '$count',
                  textAlign: TextAlign.center,
                  style: const TextStyle().copyWith(
                      color: context.color.accentContrastColor,
                      fontWeight: FontWeight.w600),
                ),
              ),
              child: Container(
                height: 40,
                width: 40,
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                    color: context.color.accentContrastColor,
                    shape: BoxShape.circle,
                    border: Border.all(
                      color: context.color.mutedContrastColor,
                    )),
                child: SvgAssets.notificationBell.toSVGSized(
                  20,
                  color: context.color.primaryContrastColor,
                ),
              ),
            ),
          ),
        ),
      );
    });
  }
}
