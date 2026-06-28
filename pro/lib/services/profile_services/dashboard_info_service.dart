import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';

class DashboardInfoService with ChangeNotifier {
  DashboardInfoModel? _dashboardInfoModel;
  DashboardInfoModel? get dashboardInfoModel => _dashboardInfoModel;

  bool isLoading = false;

  num totalOrder = 0;

  fetchDashboardInfo({trySkip = false, refreshing = false}) async {
    var url = AppUrls.dashboardInfoUrl;
    final localInfo = sPref!.getString("dashboard");
    if (localInfo != null && trySkip) {
      _dashboardInfoModel = DashboardInfoModel.fromJson(
          jsonDecode(sPref!.getString("dashboard") ?? "{}"));
      notifyListeners();
      return true;
    }
    if (!refreshing) {
      isLoading = true;
      notifyListeners();
    }
    try {
      final responseData = await NetworkApiServices()
          .getApi(url, null, headers: acceptJsonAuthHeader);

      if (responseData != null) {
        final tempData = DashboardInfoModel.fromJson(responseData);
        _dashboardInfoModel = tempData;
        totalOrder = tempData.activeOrder +
            tempData.cancelOrder +
            tempData.pendingOrder +
            tempData.completedOrder;
        sPref?.setString("dashboard", jsonEncode(tempData.toJson() ?? {}));
        isLoading = false;
        notifyListeners();
        return true;
      }
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }
}

DashboardInfoModel dashboardInfoModelFromJson(String str) =>
    DashboardInfoModel.fromJson(json.decode(str));

String dashboardInfoModelToJson(DashboardInfoModel data) =>
    json.encode(data.toJson());

class DashboardInfoModel {
  final num pendingOrder;
  final num activeOrder;
  final num completedOrder;
  final num cancelOrder;
  final num totalWithdrawnMoney;
  final num remainingBalance;
  final num orderCompletionRate;
  final num customerSatisfactionRate;

  DashboardInfoModel({
    required this.pendingOrder,
    required this.activeOrder,
    required this.completedOrder,
    required this.cancelOrder,
    required this.totalWithdrawnMoney,
    required this.remainingBalance,
    required this.orderCompletionRate,
    required this.customerSatisfactionRate,
  });

  factory DashboardInfoModel.fromJson(Map json) => DashboardInfoModel(
        pendingOrder: json["pending_order"].toString().tryToParse,
        activeOrder: json["active_order"].toString().tryToParse,
        completedOrder: json["completed_order"].toString().tryToParse,
        cancelOrder: json["cancel_order"].toString().tryToParse,
        totalWithdrawnMoney: (json["provider_balance"]?["total_withdrawn"])
            .toString()
            .tryToParse,
        remainingBalance: (json["provider_balance"]?["available_balance"])
            .toString()
            .tryToParse,
        orderCompletionRate:
            json["order_completion_rate"].toString().tryToParse,
        customerSatisfactionRate:
            json["customer_satisfaction_rate"].toString().tryToParse,
      );

  Map<String, dynamic> toJson() => {
        "pending_order": pendingOrder,
        "active_order": activeOrder,
        "completed_order": completedOrder,
        "cancel_order": cancelOrder,
        "total_withdrawn_money": totalWithdrawnMoney,
        "remaining_balance": remainingBalance,
        "order_completion_rate": orderCompletionRate,
        "customer_satisfaction_rate": customerSatisfactionRate,
      };
}
