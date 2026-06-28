import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/order_models/complete_request_history_list_model.dart';

class OrderCompleteRequestHistoryService with ChangeNotifier {
  CompleteRequestHistoryListModel? _completeRequestHistoryListModel;
  CompleteRequestHistoryListModel get completeRequestHistoryListModel =>
      _completeRequestHistoryListModel ??
      CompleteRequestHistoryListModel(orderCompleteRequest: []);

  String token = "";
  String? nextPage;
  String id = "";

  bool nextPageLoading = false;
  bool isLoading = false;

  bool nexLoadingFailed = false;

  bool shouldAutoFetch(id) => this.id != id.toString() || token.isInvalid;

  fetchCRH({required orderId, subOrderId}) async {
    _completeRequestHistoryListModel = null;
    token = getToken;
    id = subOrderId.toString();
    final url =
        "${AppUrls.completeRequestHistoryUrl}order_id=${orderId.toString()}&sub_order_id=${subOrderId.toString()}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.requestHistory, headers: commonAuthHeader);

    if (responseData != null) {
      _completeRequestHistoryListModel =
          CompleteRequestHistoryListModel.fromJson(responseData);
      nextPage = _completeRequestHistoryListModel?.pagination?.nextPageUrl;
    } else {}
    notifyListeners();
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices().getApi(
        nextPage ?? "", LocalKeys.requestHistory,
        headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = CompleteRequestHistoryListModel.fromJson(responseData);
      for (var h in tempData.orderCompleteRequest) {
        _completeRequestHistoryListModel?.orderCompleteRequest.add(h);
      }
      nextPage = tempData.pagination?.nextPageUrl;

      notifyListeners();
      return true;
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
