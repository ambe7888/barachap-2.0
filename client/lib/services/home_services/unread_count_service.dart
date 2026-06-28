import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';

class UnreadCountService {
  final ValueNotifier<num> notificationCount = ValueNotifier(0);
  final ValueNotifier<num> messageCount = ValueNotifier(0);

  UnreadCountService._init();
  static UnreadCountService? _instance;
  static UnreadCountService get instance {
    _instance ??= UnreadCountService._init();
    return _instance!;
  }

  UnreadCountService._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  fetchUnreadCounts() async {
    var url = AppUrls.unreadCountUrl;

    final responseData = await NetworkApiServices().getApi(
      url,
      null,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      debugPrint(responseData.toString());
      notificationCount.value =
          responseData["unread_notifications"].toString().tryToParse;
      messageCount.value = (responseData["unseen_message"]
              ?["client_unseen_message_count"])
          .toString()
          .tryToParse;

      return true;
    }
  }
}
