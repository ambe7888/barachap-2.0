import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/notification_services/notification_manage_service.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/notification_models/notification_list_model.dart';

class NotificationListService with ChangeNotifier {
  NotificationListModel? _notificationListModel;
  NotificationListModel get notificationListModel =>
      _notificationListModel ?? NotificationListModel(notifications: []);
  var token = "";

  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _notificationListModel == null || token.isInvalid;

  fetchNotificationList() async {
    token = getToken;
    final url = AppUrls.notificationsListUrl;
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      NotificationManageService().tryMarkingRead();
      debugPrint(getToken.toString());
      _notificationListModel = NotificationListModel.fromJson(responseData);
      nextPage = _notificationListModel?.pagination?.nextPageUrl;
    } else {}
    notifyListeners();
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .getApi(nextPage, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = NotificationListModel.fromJson(responseData);
      for (var element in tempData.notifications) {
        _notificationListModel?.notifications.add(element);
      }
      nextPage = tempData.pagination?.nextPageUrl;
    } else {
      nexLoadingFailed = true;
      Future.delayed(const Duration(seconds: 1)).then((value) {
        nexLoadingFailed = false;
        notifyListeners();
      });
    }
    nextPageLoading = false;
    notifyListeners();
  }
}
