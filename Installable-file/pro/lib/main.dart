import 'dart:convert';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/material.dart';
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
  String? description = message.data['body'] ?? "";
  String identity = message.data['identify']?.toString() ?? "";
  String type = message.data['type'] ?? "name";
  if (type == "Order") {}
  if (type == "Withdraw") {}
  if (type == "offer") {
    identity = DateTime.now().millisecond.toString();
  }
  if (type == "chat") {
    try {
      title = message.data["user_name"] ?? message.data["message_title"] ?? "";
      debugPrint("title added".toString());
      description = message.data["message"]?.toString();
      debugPrint("message added".toString());
    } catch (e) {}
  }
  NotificationHelper().triggerNotification(
    id: num.tryParse(identity.toString())?.toInt() ?? 0,
    body: description ?? message.data['body'],
    title: title,
    payload: message.data,
    channelName: type,
  );
}

final navigatorKey = GlobalKey<NavigatorState>(debugLabel: "nav_key");

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final GoogleMapsFlutterPlatform mapsImplementation =
      GoogleMapsFlutterPlatform.instance;
  if (mapsImplementation is GoogleMapsFlutterAndroid) {
    mapsImplementation.useAndroidViewSurface = true;
  }

  sPref ??= await SharedPreferences.getInstance();
  await Firebase.initializeApp();
  runApp(const MainApp());
}
