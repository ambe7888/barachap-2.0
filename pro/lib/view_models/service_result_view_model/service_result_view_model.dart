import 'package:flutter/material.dart';

class ServiceResultViewModel {
  ScrollController scrollController = ScrollController();
  final GlobalKey<RefreshIndicatorState> refreshKey =
      GlobalKey<RefreshIndicatorState>();

  ServiceResultViewModel._init();
  static ServiceResultViewModel? _instance;
  static ServiceResultViewModel get instance {
    _instance ??= ServiceResultViewModel._init();
    return _instance!;
  }

  ServiceResultViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
