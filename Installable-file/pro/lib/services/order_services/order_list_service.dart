import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:prohand/view_models/order_list_view_model/order_status_enums.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/order_models/order_list_model.dart';

class OrderListService with ChangeNotifier {
  OrderListModel? _myOrdersModel;
  OrderListModel get myOrdersModel =>
      _myOrdersModel ?? OrderListModel(allOrders: []);
  var token = "";

  var nextPage;

  bool nextPageLoading = false;
  bool isLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _myOrdersModel == null || token.isInvalid;

  get orderStatus {
    final olm = OrderListViewModel.instance;
    switch (olm.bookingStatus.value) {
      case BookingStatus.pending:
        return 0;
      case BookingStatus.accepted:
        return 1;
      case BookingStatus.complete:
        return 2;
      case BookingStatus.canceled:
        return 4;
      case BookingStatus.declined:
        return 5;
      default:
        return "";
    }
  }

  String get filters {
    String tabs = "?";
    final olm = OrderListViewModel.instance;
    if (olm.bookingStatus.value != null) {
      tabs = "${tabs}status=$orderStatus&";
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
      _myOrdersModel = OrderListModel.fromJson(responseData);
      nextPage = _myOrdersModel?.pagination?.nextPageUrl;
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
      final tempData = OrderListModel.fromJson(responseData);
      for (var element in tempData.allOrders) {
        _myOrdersModel?.allOrders.add(element);
      }
      nextPage = tempData.pagination?.nextPageUrl;
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
