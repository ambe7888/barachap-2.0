import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/order_models/order_list_model.dart';
import '../../models/staff_models/staff_list_model.dart';

class TodaysOrdersService with ChangeNotifier {
  OrderListModel? _myOrdersModel;
  OrderListModel get myOrdersModel =>
      _myOrdersModel ?? OrderListModel(allOrders: []);
  var token = "";

  List<Staff> staffs = [];
  List<String> staffsId = [];

  var nextPage;

  bool nextPageLoading = false;
  bool isLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _myOrdersModel == null || token.isInvalid;

  fetchOrder({refresh = true}) async {
    token = getToken;
    final url = AppUrls.todaysOrdersUrl;
    if (!refresh && !shouldAutoFetch) {
      isLoading = true;
      notifyListeners();
    }
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      _myOrdersModel = OrderListModel.fromJson(responseData);
      nextPage = _myOrdersModel?.pagination?.nextPageUrl;
      staffs = [];
      staffsId = [];
      _myOrdersModel?.allOrders.forEach((order) {
        if (order.staff?.id?.toString() != null &&
            !staffsId.contains(order.staff?.id?.toString())) {
          staffs.add(order.staff!);
          staffsId.add(order.staff!.id.toString());
        }
      });
    } else {}
    _myOrdersModel ??= OrderListModel(allOrders: []);
    isLoading = false;
    notifyListeners();
  }
}
