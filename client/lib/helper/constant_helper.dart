import 'package:flutter/material.dart';
import 'package:flutter_cache_manager/flutter_cache_manager.dart';
import 'package:prohandy_client/models/color_model.dart';
import 'package:prohandy_client/services/theme_service.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:timeago/timeago.dart' as timeago;

import '/services/app_string_service.dart';
import '../services/dynamics/dynamics_service.dart';
import '../services/dynamics/time_ago_helper.dart';

late DynamicsService dProvider;
late ColorModel _color;
late AppStringService asProvider;
SharedPreferences? sPref;
ColorModel get color => _color;
var _chatProviderId;

get chatProviderId => _chatProviderId?.toString();
setChatProviderId(id) {
  _chatProviderId = id;
}

var _orderId;

get orderId => _orderId;
setOrderId(id) {
  _orderId = id;
}

String get getToken {
  return sPref?.getString("token") ?? "";
}

setToken(token) {
  sPref?.setString("token", token ?? "");
}

get commonAuthHeader => {'Authorization': 'Bearer $getToken'};
get acceptJsonHeader => {'Accept': 'application/json'};
get acceptJsonAuthHeader => {
  'Accept': 'application/json',
  'Authorization': 'Bearer $getToken',
};

coreInit(BuildContext context) async {
  dProvider = Provider.of<DynamicsService>(context, listen: false);
  _color = Provider.of<ThemeService>(context, listen: false).selectedTheme;
  asProvider = Provider.of<AppStringService>(context, listen: false);
  sPref ??= await SharedPreferences.getInstance();

  try {
    final slug = sPref?.getString("lang_slug") ?? "en";
    timeago.setLocaleMessages(
      slug,
      timeAgoSupportedLangs[slug] ?? timeago.EnMessages(),
    );
    timeago.setDefaultLocale(slug);
  } catch (e) {
    debugPrint("Error setting locale for timeago: $e");
  }
}

final customCacheManager = CacheManager(
  Config(
    'customCacheKey',
    stalePeriod: const Duration(days: 7),
    maxNrOfCacheObjects: 100,
  ),
);
