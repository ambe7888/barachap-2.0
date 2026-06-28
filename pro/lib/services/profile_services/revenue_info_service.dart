import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../view_models/order_list_view_model/order_status_enums.dart';

class RevenueInfoService with ChangeNotifier {
  Map revenueInfo = {};

  RevenueInfoType revenueInfoType = RevenueInfoType.thisWeek;

  setRevenueInfoType(RevenueInfoType type) {
    revenueInfoType = type;
    fetchRevenueInfo();
    notifyListeners();
  }

  fetchRevenueInfo() async {
    final data = {
      'interval': revenueInfoKeyValues[revenueInfoType] ?? "this_week",
    };
    final responseData = await NetworkApiServices().postApi(
      data,
      AppUrls.revenueInfoUrl,
      LocalKeys.revenueInfo,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      final tempData = responseData['total_earnings'];
      var tempData2;
      if (tempData is! List) {
        debugPrint(tempData.toString());
        tempData2 = tempData.values.toList();
        for (var element in tempData2) {
          final v = element.values.toList() as List;
          debugPrint(v.toString());

          revenueInfo.addAll(
              {v.firstOrNull.toString(): v.lastOrNull.toString().tryToParse});
        }
      }
      if (tempData is List) {
        for (var element in tempData) {
          final v = element.values.toList() as List;
          debugPrint(v.toString());
          revenueInfo.addAll(
              {v.firstOrNull.toString(): v.lastOrNull.toString().tryToParse});
        }
      }
      return true;
    }
  }
}

enum RevenueInfoType {
  thisWeek,
  thisMonth,
  lastMonth,
  thisYear,
  lastYear,
}

final revenueInfoTypeValue = EnumValues({
  LocalKeys.thisWeek: RevenueInfoType.thisWeek,
  LocalKeys.thisMonth: RevenueInfoType.thisMonth,
  LocalKeys.lastMonth: RevenueInfoType.lastMonth,
  LocalKeys.thisYear: RevenueInfoType.thisYear,
  LocalKeys.lastYear: RevenueInfoType.lastYear,
});
final revenueInfoKeyValues = {
  RevenueInfoType.thisWeek: "this_week",
  RevenueInfoType.thisMonth: "this_month",
  RevenueInfoType.lastMonth: "last_month",
  RevenueInfoType.thisYear: "this_year",
  RevenueInfoType.lastYear: "last_year",
};
