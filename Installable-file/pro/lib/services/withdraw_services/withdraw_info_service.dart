import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/withdraw_history_model.dart';
import 'package:prohand/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:prohand/view_models/order_list_view_model/order_status_enums.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';

class WithdrawInfoService with ChangeNotifier {
  WithdrawHistoryModel? _myOrdersModel;
  WithdrawHistoryModel get myOrdersModel =>
      _myOrdersModel ?? WithdrawHistoryModel();
  var token = "";

  var nextPage;

  bool nextPageLoading = false;
  bool isLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _myOrdersModel == null || token.isInvalid;

  String get filters {
    String tabs = "?";
    final olm = OrderListViewModel.instance;
    if (olm.bookingStatus.value != null) {
      tabs =
          "${tabs}status=${BookingStatus.values.indexOf(olm.bookingStatus.value!)}&";
    }
    if (olm.paymentStatus.value != null) {
      tabs =
          "${tabs}payment=${PaymentStatus.values.indexOf(olm.paymentStatus.value!)}";
    }
    return tabs;
  }

  fetchOrderList({refresh = true}) async {
    token = getToken;
    final url = AppUrls.ordersListUrl + filters;
    if (!refresh && !shouldAutoFetch) {
      isLoading = true;
      notifyListeners();
    }
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      _myOrdersModel = WithdrawHistoryModel.fromJson(responseData);
    } else {}
    isLoading = false;
    notifyListeners();
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .getApi(nextPage, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = WithdrawHistoryModel.fromJson(responseData);
    } else {
      nexLoadingFailed = true;
      Future.delayed(const Duration(seconds: 1)).then((value) {
        nexLoadingFailed = false;
        notifyListeners();
      });
    }
    nextPageLoading = false;
    notifyListeners();
  }
}
