import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/services/notification_services/notification_list_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';
import 'package:prohand/view_models/notification_list_view_model/notification_list_view_model.dart';
import 'package:prohand/views/notification_list_view/components/notification_tile.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';

class NotificationListView extends StatelessWidget {
  const NotificationListView({super.key});

  @override
  Widget build(BuildContext context) {
    final nlm = NotificationListViewModel.instance;
    final nlProvider =
        Provider.of<NotificationListService>(context, listen: false);
    nlm.scrollController.addListener(() {
      nlm.tryToLoadMore(context);
    });
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.notifications),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await nlProvider.fetchNotificationList();
        },
        child: Scrollbar(
          controller: nlm.scrollController,
          child: CustomFutureWidget(
            function: nlProvider.shouldAutoFetch
                ? nlProvider.fetchNotificationList()
                : null,
            shimmer: const CustomPreloader(),
            child: Consumer<NotificationListService>(
                builder: (context, nl, child) {
              return nl.notificationListModel.notifications.isEmpty
                  ? EmptyWidget(title: LocalKeys.noNotificationsGotYet)
                  : CustomScrollView(
                      controller: nlm.scrollController,
                      physics: const AlwaysScrollableScrollPhysics(),
                      slivers: [
                        const SizedBox().divider.toSliver,
                        SliverList.separated(
                          itemBuilder: (context, index) {
                            final notification =
                                nl.notificationListModel.notifications[index];
                            return NotificationTile(
                              notification: notification,
                            );
                          },
                          separatorBuilder: (context, index) =>
                              const SizedBox().divider.hp20,
                          itemCount:
                              nl.notificationListModel.notifications.length,
                        ),
                        16.toHeight.toSliver,
                        if (nl.nextPage != null && !nl.nexLoadingFailed)
                          ScrollPreloader(loading: nl.nextPageLoading).toSliver,
                        16.toHeight.toSliver,
                      ],
                    );
            }),
          ),
        ),
      ),
    );
  }
}
