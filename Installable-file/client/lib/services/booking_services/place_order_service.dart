import 'dart:convert';
import 'dart:developer';
import 'dart:io';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:prohandy_client/customization.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:provider/provider.dart';

import '../../data/network/network_api_services.dart';
import '../../models/order_models/order_response_model.dart';
import '../profile_services/profile_info_service.dart';

class PlaceOrderService with ChangeNotifier {
  OrderResponseModel? _orderResponseModel;

  OrderResponseModel get orderResponseModel =>
      _orderResponseModel ?? OrderResponseModel();

  tryPlacingOrder(services, addons, gateway, File? file, coupon) async {
    var url = AppUrls.placeOrderUrl;
    var data = {
      'all_services': jsonEncode({"all_services": services}),
      'addons_services': jsonEncode({"addons_services": addons}),
      'selected_payment_gateway': gateway.toString(),
      'coupon_code': coupon.toString(),
    };
    var request = http.MultipartRequest('POST', Uri.parse(url));
    request.fields.addAll(data);

    if (gateway == "manual_payment" && file != null) {
      request.files.add(
          await http.MultipartFile.fromPath('manual_payment_image', file.path));
    }
    log(jsonEncode(data));
    request.headers.addAll(acceptJsonAuthHeader);
    final responseData = await NetworkApiServices()
        .postWithFileApi(request, LocalKeys.payAndConfirmOrder);

    if (responseData != null) {
      _orderResponseModel = OrderResponseModel.fromJson(responseData);
      return true;
    }
  }

  updatePayment(BuildContext context, {dynamic id}) async {
    var url = AppUrls.orderPaymentUpdateUrl;
    if ((orderResponseModel.orderDetails?.id ?? id) == null) {
      LocalKeys.orderNotFound.showToast();
      return;
    }
    final pi = Provider.of<ProfileInfoService>(context, listen: false);
    var data = {
      'order_id': (id ?? orderResponseModel.orderDetails!.id).toString()
    };
    debugPrint(data.toString());
    debugPrint(pi.profileInfoModel.userDetails?.email.toString());
    var headers = {
      'Accept': 'application/json',
      'Authorization': 'Bearer $getToken',
      "X-HMAC": (pi.profileInfoModel.userDetails?.email ?? "")
          .toHmac(secret: paymentUpdateKey),
    };
    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.payAndConfirmOrder, headers: headers);

    if (responseData != null) {
      _orderResponseModel?.orderDetails?.paymentStatus = "1";
      notifyListeners();
      return true;
    }
  }
}

/* services dummy

{
    "all_services": [
        {
            "service_id": "17",
            "staff_id": "1",
            "location_id": 1,
            "date": "2024-08-30",
            "schedule": "10:00AM - 1:00PM",
            "order_note": "Test Order notes data"
        },
        {
            "service_id": "20",
            "staff_id": "4",
            "location_id": 3,
            "date": "2024-08-30",
            "schedule": "10:00AM - 1:00PM",
            "order_note": "Test Order notes data"
        }
    ]
}

*/