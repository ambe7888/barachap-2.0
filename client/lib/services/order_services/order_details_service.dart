import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../view_models/order_details_view_model/order_details_view_model.dart';

class OrderDetailsService with ChangeNotifier {
  OrderResponseModel? _orderDetailsModel;
  OrderResponseModel get orderDetailsModel =>
      _orderDetailsModel ?? OrderResponseModel();

  String token = "";
  String id = "";

  bool shouldAutoFetch(id) => id.toString() != this.id || token.isInvalid;

  fetchOrderDetails({required orderId}) async {
    _orderDetailsModel = null;
    token = getToken;
    id = orderId.toString();
    final url = "${AppUrls.orderDetailsUrl}/${orderId.toString()}";
    final responseData = await NetworkApiServices().getApi(
      url,
      LocalKeys.orderDetails,
      headers: commonAuthHeader,
    );

    if (responseData != null) {
      _orderDetailsModel = OrderResponseModel.fromJson(responseData);

      debugPrint(
        "order details is ${orderDetailsModel.orderDetails?.subOrders?.firstOrNull?.refundDetails}"
            .toString(),
      );
    } else {}
    notifyListeners();
  }

  tryCompleteOrder({required orderId, required subOrderId}) async {
    var url =
        "${AppUrls.acceptOrderCompeteRequestUrl}?order_id=$orderId&sub_order_id=$subOrderId";
    var data = {};

    final responseData = await NetworkApiServices().postApi(
      data,
      url,
      LocalKeys.cancelOrder,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      try {
        _orderDetailsModel
            ?.orderDetails
            ?.subOrders
            ?.firstWhere((i) => i.id == subOrderId)
            .completionHistory
            ?.lastOrNull
            ?.status = "1";
        _orderDetailsModel
            ?.orderDetails
            ?.subOrders
            ?.firstWhere((i) => i.id == subOrderId)
            .status = "2";
        _orderDetailsModel
            ?.orderDetails
            ?.subOrders
            ?.firstWhere((i) => i.id == subOrderId)
            .completionHistory
            ?.lastOrNull
            ?.message = LocalKeys.orderCompletionRequestAccepted;
      } catch (e) {
        debugPrint(e.toString());
      }
      notifyListeners();
      return true;
    }
  }

  tryDecliningRequest({
    required orderId,
    required subOrderId,
    required String declineReason,
  }) async {
    var url = AppUrls.declineOrderCompeteRequestUrl;
    var data = {
      'order_id': orderId.toString(),
      'sub_order_id': subOrderId.toString(),
      'decline_reason': declineReason,
    };

    final responseData = await NetworkApiServices().postApi(
      data,
      url,
      LocalKeys.cancelOrder,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      try {
        _orderDetailsModel
            ?.orderDetails
            ?.subOrders
            ?.firstWhere((i) => i.id == subOrderId)
            .completionHistory
            ?.lastOrNull
            ?.status = "2";
        _orderDetailsModel
            ?.orderDetails
            ?.subOrders
            ?.firstWhere((i) => i.id == subOrderId)
            .completionHistory
            ?.lastOrNull
            ?.message = declineReason;
      } catch (e) {}
      notifyListeners();
      return true;
    }
  }

  tryCancelOrder({subOrderId}) async {
    var url = AppUrls.orderCancelUrl;
    final odm = OrderDetailsViewModel.instance;
    final gatewayFields = odm.selectedGateway.value?.field ?? [];
    final fieldValues = {};
    for (var i = 0; i < gatewayFields.length; i++) {
      fieldValues.putIfAbsent(
        gatewayFields[i].toLowerCase().replaceAll(" ", "_"),
        () => odm.inputFieldControllers[i].text,
      );
    }
    var data = {
      'gateway_id': odm.selectedGateway.value?.id.toString() ?? "",
      'gateway_fields': jsonEncode(fieldValues),
      'sub_order_ids': jsonEncode([subOrderId]),
      'cancel_reason': odm.reasonController.text,
      "order_id": orderDetailsModel.orderDetails?.id?.toString() ?? "",
    };

    final responseData = await NetworkApiServices().postApi(
      data,
      url,
      LocalKeys.cancelOrder,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      LocalKeys.orderCancelledSuccessfully.showToast();
      odm.refreshKey.currentState?.show();
      notifyListeners();
      return true;
    }
    return false;
  }

  tryRefundRequest({subOrderId}) async {
    var url = AppUrls.orderRefundRequestUrl;
    final odm = OrderDetailsViewModel.instance;
    final gatewayFields = odm.selectedGateway.value?.field ?? [];
    final fieldValues = {};
    for (var i = 0; i < gatewayFields.length; i++) {
      fieldValues.putIfAbsent(
        gatewayFields[i].toLowerCase().replaceAll(" ", "_"),
        () => odm.inputFieldControllers[i].text,
      );
    }
    var data = {
      'gateway_id': odm.selectedGateway.value?.id.toString() ?? "",
      'gateway_fields': jsonEncode(fieldValues),
      'sub_order_ids': jsonEncode([subOrderId]),
      'cancel_reason': odm.reasonController.text,
      "order_id": orderDetailsModel.orderDetails?.id?.toString() ?? "",
    };

    final responseData = await NetworkApiServices().postApi(
      data,
      url,
      LocalKeys.cancelOrder,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      LocalKeys.refundRequestSentSuccessfully.showToast();
      odm.refreshKey.currentState?.show();
      notifyListeners();
      return true;
    }
    return false;
  }
}
