import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import '/helper/extension/string_extension.dart';
import '/helper/local_keys.g.dart';

class LandingViewModel {
  DateTime? currentBackPressTime;
  ValueNotifier currentIndex = ValueNotifier(0);
  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();

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

  Future<bool> willPopFunction(BuildContext context) async {
    if (currentIndex.value != 0) {
      currentIndex.value = 0;
      return Future.value(false);
    }

    DateTime now = DateTime.now();
    if (currentBackPressTime == null ||
        now.difference(currentBackPressTime!) > const Duration(seconds: 2)) {
      currentBackPressTime = now;
      LocalKeys.pressAgainToExit.showToast();
      return Future.value(false);
    }
    SystemNavigator.pop();

    return true;
  }
}
