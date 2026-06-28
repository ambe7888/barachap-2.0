import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohandy_client/services/home_services/unread_count_service.dart';
import 'package:prohandy_client/views/notification_list_view/notification_list_view.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '../../helper/svg_assets.dart';

class Notifications extends StatelessWidget {
  const Notifications({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final ucs = UnreadCountService.instance;
    return GestureDetector(
      onTap: () {
        context.toPage(const NotificationListView());
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
  }
}
