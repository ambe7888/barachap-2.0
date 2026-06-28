import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/view_models/order_list_view_model/order_status_enums.dart';

class HomeViewModel {
  final ScrollController scrollController = ScrollController();

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();

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

enum BarChartTye { weekly, monthly }

var barChartTye = EnumValues({
  LocalKeys.weekly:BarChartTye.weekly,
  LocalKeys.monthly:BarChartTye.monthly,
});
