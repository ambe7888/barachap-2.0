import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:prohandy_client/views/landing_view/landing_view.dart';

import '/helper/extension/string_extension.dart';
import '/helper/local_keys.g.dart';

class LandingViewModel {
  DateTime? currentBackPressTime;
  ValueNotifier currentIndex = ValueNotifier(0);
  GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();
  BuildContext? context;

  LandingViewModel._init();
  static LandingViewModel? _instance;
  static LandingViewModel get instance {
    _instance ??= LandingViewModel._init();
    return _instance!;
  }

  LandingViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void setNavIndex(int value) async {
    if (value == currentIndex.value) {
      return;
    }
    currentIndex.value = value;
  }

  void setNavIndexP(int value) {
    if (value == currentIndex.value) {
      return;
    }
    currentIndex.value = value;
  }

  setContext(BuildContext? context) {
    this.context = context;
  }

  void prepareForNavigation() {
    // Create a new GlobalKey to avoid duplicates
    scaffoldKey = GlobalKey<ScaffoldState>();
  }

  void navigateToLanding(BuildContext context) {
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => LandingView()),
      (route) => false,
    );
  }

  bool willPopFunction(BuildContext context) {
    this.context = context;
    if (currentIndex.value != 0) {
      currentIndex.value = 0;
      return false;
    }

    DateTime now = DateTime.now();
    if (currentBackPressTime == null ||
        now.difference(currentBackPressTime!) > const Duration(seconds: 2)) {
      currentBackPressTime = now;
      LocalKeys.pressAgainToExit.showToast();
      return false;
    }
    SystemNavigator.pop();
    return true;
  }
}
