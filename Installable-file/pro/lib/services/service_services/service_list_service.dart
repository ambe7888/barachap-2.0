import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/service_models/service_list_model.dart';

import '../../data/network/network_api_services.dart';
import '../../view_models/my_services_view_model/my_services_view_model.dart';

class ServiceListService with ChangeNotifier {
  ServiceListModel? _serviceListModel;
  ServiceListModel get serviceListModel =>
      _serviceListModel ?? ServiceListModel();

  String token = "";
  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  bool get shouldAutoFetch => _serviceListModel == null || token.isInvalid;

  fetchServiceList({bool refresh = false}) async {
    final msm = MyServicesViewModel.instance;
    String filter =
        "?title=${msm.titleController.text}&status=${msm.serviceStatusValues[msm.selectedStatus.value]}";
    token = getToken;
    if (!refresh) {
      isLoading = true;
      notifyListeners();
    }
    var url = AppUrls.serviceListUrl + filter;
    debugPrint(url.toString());

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.myServices, headers: acceptJsonAuthHeader);

    try {
      if (responseData != null) {
        final tempData = ServiceListModel.fromJson(responseData);
        _serviceListModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
      } else {
        _serviceListModel ??= ServiceListModel();
      }
    } catch (e) {
      debugPrint(e.toString());
      log(e.toString());
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .getApi(nextPage, LocalKeys.orderList, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = ServiceListModel.fromJson(responseData);
      tempData.allServices?.forEach((element) {
        _serviceListModel?.allServices?.add(element);
      });
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
