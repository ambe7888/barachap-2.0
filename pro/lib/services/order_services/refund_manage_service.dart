import 'package:flutter/material.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../models/order_models/refund_details_model.dart';

class RefundManageService with ChangeNotifier {
  RefundDetailsModel? _refundDetailsModel;
  RefundDetailsModel get refundDetailsModel =>
      _refundDetailsModel ?? RefundDetailsModel();
  var token = "";

  var refundId;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;

  bool shouldAutoFetch(id) =>
      id.toString() != refundId?.toString() || token.isInvalid;

  fetchRefundDetails({id}) async {
    refundId = id;
    token = getToken;
    final url = "${AppUrls.myRefundDetailsUrl}/$id";
    final responseData = await NetworkApiServices().getApi(
      url,
      LocalKeys.orderList,
      headers: commonAuthHeader,
    );

    if (responseData != null) {
      _refundDetailsModel = RefundDetailsModel.fromJson(responseData);
    } else {}
    notifyListeners();
  }
}
