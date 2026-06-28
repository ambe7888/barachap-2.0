import 'dart:convert';
import 'dart:developer';

import 'package:flutter/material.dart';
import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/local_keys.g.dart';
import '../../helper/constant_helper.dart';
import '../../models/payment_gateway_model.dart';

class PaymentGatewayService with ChangeNotifier {
  List<Gateway> gatewayList = [];
  Gateway? selectedGateway;
  bool isLoading = false;
  DateTime? authPayED;
  bool doAgree = false;

  bool get shouldAutoFetch => gatewayList.isEmpty;

  setSelectedGareaway(value) {
    selectedGateway = value;
    notifyListeners();
  }

  setDoAgree(value) {
    doAgree = value;
    notifyListeners();
  }

  setAuthPayED(value) {
    if (value == authPayED) {
      return;
    }
    authPayED = value;
    notifyListeners();
  }

  bool itemSelected(value) {
    if (selectedGateway == null) {
      return false;
    }
    return selectedGateway == value;
  }

  setIsLoading(value) {
    isLoading = value;
    notifyListeners();
  }

  resetGateway() {
    selectedGateway = null;
    authPayED = null;
    doAgree = false;
    notifyListeners();
  }

  fetchGateways() async {
    if (gatewayList.isNotEmpty) {
      return;
    }

    try {
      var responseData = await NetworkApiServices().getApi(
          AppUrls.paymentGatewayUrl, LocalKeys.paymentGateway,
          headers: acceptJsonAuthHeader);
      if (responseData != null) {
        final modifiedResponse = [];
        for (var i = 0; i < responseData.length; i++) {
          Map gateway = responseData.values.toList()[i];
          gateway.putIfAbsent("name", () => responseData.keys.toList()[i]);
          modifiedResponse.add(gateway);
        }
        debugPrint(modifiedResponse.toString());
        log(jsonEncode(modifiedResponse));
        final tempData =
            PaymentGatewayModel.fromJson({"data": modifiedResponse});
        gatewayList = tempData.data ?? [];
      }
      notifyListeners();
    } catch (err) {
      debugPrint(err.toString());
    }
  }
}
