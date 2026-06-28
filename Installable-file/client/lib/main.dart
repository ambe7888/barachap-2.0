import 'dart:convert';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:google_maps_flutter_android/google_maps_flutter_android.dart';
import 'package:google_maps_flutter_platform_interface/google_maps_flutter_platform_interface.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'app.dart';
import 'helper/constant_helper.dart';
import 'helper/firebase_messaging_helper.dart';
import 'helper/notification_helper.dart';

@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();

  await setupFlutterNotifications();
  final messageData = message.data;
  final sPref = await SharedPreferences.getInstance();
  final strings = sPref.getString('translated_string') ?? "{}";
  var translatedString = jsonDecode(strings);

  String title = message.data['title'] ?? "";
  String description = message.data['body'] ?? "";
  String identity = message.data['identity']?.toString() ?? "";
  String type = message.data['type'] ?? "name";
  if (type == "Order") {
    title = description;
    description = "${translatedString["Order Id"] ?? "Order Id"}: #$identity";
  }
  if (type == "Withdraw") {
    title = description;
    description = "${translatedString["Id"] ?? "Id"}: #$identity";
  }
  if (type == "offer") {
    identity = DateTime.now().millisecond.toString();
  }
  if (type == "message") {
    var liveChatData = jsonDecode(message.data["livechat"] ?? "{}");

    try {
      title = liveChatData["client"]?["first_name"]?.toString() ?? "";

      description =
          jsonDecode(message.data['body'] ?? "{}")?["message"]?.toString() ??
          "";
    } catch (e) {}
  }
  debugPrint(message.data.toString());
  try {
    NotificationHelper().triggerNotification(
      id: num.tryParse(identity.toString())?.toInt() ?? 0,
      body: description,
      title: title,
      payload: message.data,
      channelName: type,
    );
  } catch (e) {
    rethrow;
  }
}

final navigatorKey = GlobalKey<NavigatorState>(debugLabel: "nav_key");

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final GoogleMapsFlutterPlatform mapsImplementation =
      GoogleMapsFlutterPlatform.instance;
  if (mapsImplementation is GoogleMapsFlutterAndroid) {
    mapsImplementation.useAndroidViewSurface = true;
  }
  if (kDebugMode) {
    // This will give you more detailed widget tree info
    debugPaintSizeEnabled = false; // Set to true if you want visual debugging
  }

  await Firebase.initializeApp();
  sPref ??= await SharedPreferences.getInstance();

  runApp(const MainApp());
}
