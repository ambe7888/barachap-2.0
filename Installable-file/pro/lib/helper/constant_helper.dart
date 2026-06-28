import 'package:flutter/material.dart';
import 'package:prohand/models/color_model.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:timeago/timeago.dart' as timeago;

import '/services/app_string_service.dart';
import '../services/dynamics_services/dynamics_service.dart';
import '../services/dynamics_services/time_ago_helper.dart';

late DynamicsService dProvider;
late ColorModel color;
late AppStringService asProvider;
SharedPreferences? sPref;

var _chatClientId;

get chatClientId => _chatClientId;
setChatClientId(id) {
  _chatClientId = id;
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
get acceptJsonAuthHeader =>
    {'Accept': 'application/json', 'Authorization': 'Bearer $getToken'};

coreInit(BuildContext context) async {
  dProvider = Provider.of<DynamicsService>(context, listen: false);
  color = Provider.of<ThemeService>(context, listen: false).selectedTheme;
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
