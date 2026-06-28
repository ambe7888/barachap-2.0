import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/order_models/order_details_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';

class OrderDetailsService with ChangeNotifier {
  OrderDetailsModel? _orderDetailsModel;
  OrderDetailsModel get orderDetailsModel =>
      _orderDetailsModel ?? OrderDetailsModel();

  String token = "";
  String id = "";

  bool shouldAutoFetch(id) => id.toString() != this.id || token.isInvalid;

  fetchOrderDetails({required orderId}) async {
    _orderDetailsModel = null;
    token = getToken;
    debugPrint(getToken.toString());
    id = orderId.toString();
    final url = "${AppUrls.orderDetailsUrl}/${orderId.toString()}";
    debugPrint(token.toString());
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.orderDetails, headers: commonAuthHeader);

    if (responseData != null) {
      _orderDetailsModel = OrderDetailsModel.fromJson(responseData);
    } else {}
    notifyListeners();
  }

  tryAcceptOrder({required orderId, required id}) async {
    var url = AppUrls.orderAcceptUrl;
    var data = {
      "order_id": orderId?.toString(),
      "sub_order_id": id?.toString(),
      "status": "1",
    };

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.acceptOrder,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        _orderDetailsModel?.orderDetails?.status = "1";
        LocalKeys.orderAcceptedSuccessfully.showToast();
      } catch (e) {}
      notifyListeners();
      return true;
    }
  }

  tryDeliveredOrder({required orderId, required id}) async {
    var url = "${AppUrls.orderAcceptUrl}?order_id=$orderId&sub_order_id=$id";
    var data = {};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.markAsDelivered,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        _orderDetailsModel?.orderDetails?.status = "3";
        LocalKeys.orderMarkedAsDeliveredSuccessfully.showToast();
      } catch (e) {}
      notifyListeners();
      return true;
    }
  }

  sendOrderCompletionRequest({required orderId, required id}) async {
    var url =
        "${AppUrls.completeRequestUrl}?order_id=$orderId&sub_order_id=$id&status=2";
    var data = {};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.submitCompletionRequest,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.orderCompletionRequestSentSuccessfully.showToast();
      return true;
    }
  }

  tryCancelOrder({required orderId, required id}) async {
    var url = AppUrls.orderCancelUrl;
    var data = {
      "order_id": orderId.toString(),
      "sub_order_id": id?.toString(),
    };

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.cancelOrder,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _orderDetailsModel?.orderDetails?.status = "4";
      LocalKeys.orderCancelledSuccessfully.showToast();
      notifyListeners();
      return true;
    }
  }

  tryDeclineOrder({required orderId, required id}) async {
    var url =
        "${AppUrls.orderDeclineUrl}?order_id=$orderId&sub_order_id=$id&status=5";
    var data = {};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.cancelOrder,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _orderDetailsModel?.orderDetails?.status = "5";
      LocalKeys.orderDeclinedSuccessfully.showToast();
      notifyListeners();
      return true;
    }
  }

  tryDeclineRefund({required id, oID}) async {
    var url = AppUrls.refundDeclineUrl;
    var data = {'refund_request_id': '$id', 'status': '3'};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.cancelOrder,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _orderDetailsModel?.orderDetails?.status = "5";
      LocalKeys.refundDeclinedSuccessfully.showToast();
      notifyListeners();
      return true;
    }
  }

  tryAcceptRefund({required id, oID}) async {
    var url = AppUrls.refundAcceptUrl;
    var data = {'refund_request_id': '$id', 'status': '1'};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.cancelOrder,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _orderDetailsModel?.orderDetails?.status = "5";
      LocalKeys.refundAcceptedSuccessfully.showToast();
      notifyListeners();
      return true;
    }
  }
}
