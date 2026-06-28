import 'dart:async';
import 'dart:convert';
import 'dart:developer';
import 'dart:io';

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

import '../main.dart';
import '../services/push_notification_service.dart';
import '../views/order_details_view/order_details_view.dart';
import 'constant_helper.dart';
import 'firebase_messaging_helper.dart';

class NotificationHelper {
  StreamSubscription<String?>? streamSubscription;

  notificationAppLaunchChecker(BuildContext context) async {
    print(
        '${notificationDetails?.notificationResponse?.payload}App launch notification details');
    if (notificationDetails?.notificationResponse?.payload != null) {
      final payload = jsonDecode(
          notificationDetails?.notificationResponse?.payload ?? "{}");
      await Future.delayed(const Duration(milliseconds: 10));
      proceedRouting(payload);
      if (payload["type"].toString().contains("order")) {
        print('from app launch');
      }
      setNotificationDetails(null);
    }
  }

  streamListener(BuildContext context) {
    streamSubscription ??= selectNotificationStream.stream.listen(
      (event) async {
        bool notNavigated = true;
        if (notNavigated) {
          debugPrint(
              '$selectedNotificationPayload App launch notification details');
          final payLoad = jsonDecode(selectedNotificationPayload ?? "{}");
          debugPrint("Notification type is ${payLoad["type"]}".toString());
          debugPrint(payLoad.toString());
          proceedRouting(payLoad);
          notNavigated = false;
        }
      },
    );
  }

  FirebaseMessaging messaging = FirebaseMessaging.instance;
  initiateNotification(BuildContext context) async {
    final pnProvider = PushNotificationService();
    if (pnProvider.userToken != null) {
      print('User fire token: ${pnProvider.userToken}');
      return;
    }
    NotificationSettings settings = await messaging.requestPermission(
        alert: false,
        announcement: false,
        badge: true,
        carPlay: false,
        criticalAlert: false,
        provisional: false,
        sound: false);
    try {
      if (Platform.isIOS) {
        await messaging.getAPNSToken();
        pnProvider.setUserToken(await messaging.getToken());
      } else {
        pnProvider.setUserToken(await messaging.getToken());
      }
    } catch (e) {}
    print('User granted permission: ${settings.authorizationStatus}');
    print('User fire token: ${pnProvider.userToken}');

    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      int? msgId = message.data['id'] is String
          ? int.tryParse(message.data['id'])
          : message.data['id'];

      String title = message.data['title'] ?? "";
      String? description = message.data['body'] ?? "";
      String orderId = message.data['sub_order_id']?.toString() ?? "";
      if (message.data['type'] == "order") {
        msgId = int.tryParse(orderId);
      }
      if (message.data['type'] == "chat") {
        if (message.data["user_id"]?.toString() == chatClientId) {
          return;
        }

        try {
          title =
              message.data["user_name"] ?? message.data["message_title"] ?? "";
          debugPrint("title added".toString());
          description = message.data["message"]?.toString();
          debugPrint("message added".toString());
        } catch (e) {
          log(e.toString());
        }
      }
      log(message.data.toString());

      debugPrint("notification data is".toString());
      debugPrint(message.data.toString());
      debugPrint(message.data.toString());
      selectedNotificationPayload = jsonEncode(message.data);
      NotificationHelper().triggerNotification(
          id: msgId ?? 1,
          body: description ?? message.data['body'],
          title: title ?? message.data["title"],
          payload: message.data);

      if (message.notification != null) {
        print('Message also contained a notification: ${message.notification}');
      }
    });

    messaging.subscribeToTopic('provider');
  }

  triggerNotification(
      {required int id,
      required String title,
      required String body,
      payload,
      String? channelName}) {
    debugPrint(payload.toString());
    flutterLocalNotificationsPlugin.show(
        id,
        title,
        body,
        NotificationDetails(
          android: AndroidNotificationDetails(
            '$id',
            channelName ?? 'channelName',
            priority: Priority.max,
            importance: Importance.max,
            visibility: NotificationVisibility.public,
          ),
        ),
        payload: jsonEncode(payload));
  }

  notificationsSetup() async {
    final DarwinInitializationSettings initializationSettingsDarwin =
        DarwinInitializationSettings();

    flutterLocalNotificationsPlugin.initialize(
      InitializationSettings(
        android: const AndroidInitializationSettings('notification_icon'),
        iOS: initializationSettingsDarwin,
      ),
      onDidReceiveBackgroundNotificationResponse: staticFunctionOnForeground,
      onDidReceiveNotificationResponse: staticFunctionOnForeground,
    );
    final AndroidFlutterLocalNotificationsPlugin? androidImplementation =
        flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>();
    final IOSFlutterLocalNotificationsPlugin? iosImplementation =
        flutterLocalNotificationsPlugin.resolvePlatformSpecificImplementation<
            IOSFlutterLocalNotificationsPlugin>();

    if (Platform.isAndroid) {
      await androidImplementation?.requestNotificationsPermission();
    } else if (Platform.isIOS) {
      await iosImplementation?.requestPermissions();
    }

    FirebaseMessaging.onBackgroundMessage(firebaseMessagingBackgroundHandler);

    if (!kIsWeb) {
      await setupFlutterNotifications();
    }

    setNotificationDetails(await flutterLocalNotificationsPlugin
        .getNotificationAppLaunchDetails());
    debugPrint((notificationDetails?.notificationResponse?.payload).toString());
  }
}

proceedRouting(Map payLoad) {
  String type = payLoad['type'] ?? "name";
  debugPrint(payLoad.toString());
  if (type == "order") {
    String orderId = payLoad['identify']?.toString() ?? "";
    navigatorKey.currentState
        ?.pushNamed(OrderDetailsView.routeName, arguments: orderId.toString());
    return;
  }
  if (type == "Offer") {
    String offerId = payLoad['identify']?.toString() ?? "";
    if (orderId != null) {
      navigatorKey.currentState?.pop();
    }
  }
  if (type == "chat") {
    var liveChatData = jsonDecode(payLoad["livechat"] ?? "{}");

    try {
      if (chatClientId.toString() == liveChatData["id"].toString()) {
        return;
      }
      if (chatClientId != null) {
        navigatorKey.currentState?.pop();
      }
    } catch (e) {
      log((liveChatData is Map).toString());
      log(e.toString());
    }
  }
}

var safsd = {
  "google_id": null,
  "google_2fa_secret": "HATKCPGN5WGPJEFU",
  "email_verify_token": "876626",
  "user_active_inactive_status": 1,
  "is_email_verified": "1",
  "about": null,
  "created_at": "2023-01-23T02:03:28.000000Z",
  "check_work_availability": 1,
  "user_type": 1,
  "updated_at": "2024-03-10T11:53:54.000000Z",
  "id": 1,
  "state_id": 1,
  "first_name": "Test",
  "email": "tclient@gmail.com",
  "image": "1680500462-642a66ee548e7.jpg",
  "last_name": "Client",
  "email_verified_at": null,
  "terms_condition": 1,
  "firebase_device_token": null,
  "deleted_at": null,
  "is_suspend": 0,
  "facebook_id": null,
  "check_online_status": "2024-03-10T11:53:54.000000Z",
  "google_2fa_enable_disable_disable": 0,
  "phone": "0189232",
  "user_verified_status": 1,
  "hourly_rate": 0,
  "github_id": null,
  "experience_level": "junior",
  "country_id": 1,
  "username": "client",
  "city_id": 1
};
