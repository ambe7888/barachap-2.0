import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';

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
      notificationCount.value =
          responseData["unread_notifications"].toString().tryToParse;
      messageCount.value = (responseData["unseen_message"]
              ?["provider_unseen_message_count"])
          .toString()
          .tryToParse;
      debugPrint("message count ${messageCount.value}".toString());
      messageCount.notifyListeners();
      return true;
    }
  }
}
