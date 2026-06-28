import 'package:flutter/material.dart';

class HomeViewModel {
  final ScrollController scrollController = ScrollController();

  ValueNotifier appBarSize = ValueNotifier(0.0);

  bool get isAppBarShrink {
    return scrollController.hasClients &&
        scrollController.offset > (200 - kToolbarHeight);
  }

  HomeViewModel._init();
  static HomeViewModel? _instance;
  static HomeViewModel get instance {
    _instance ??= HomeViewModel._init();
    return _instance!;
  }

  HomeViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
