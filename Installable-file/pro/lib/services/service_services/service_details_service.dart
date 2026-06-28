import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/service_models/service_details_model.dart';

class ServiceDetailsService with ChangeNotifier {
  ServiceDetailsModel? _serviceDetailsModel;

  ServiceDetailsModel get serviceDetailsModel =>
      _serviceDetailsModel ?? ServiceDetailsModel();

  String token = '';

  shouldAutoFetch(id) =>
      _serviceDetailsModel?.allServices == null || token.isInvalid;

  fetchServiceDetails(id) async {
    var url = "${AppUrls.serviceDetailsUrl}/$id";
    token = getToken;
    if (_serviceDetailsModel?.allServices?.id.toString() != id.toString()) {
      _serviceDetailsModel = null;
    }
    try {
      final responseData = await NetworkApiServices()
          .getApi(url, LocalKeys.serviceDetails, headers: acceptJsonAuthHeader);

      if (responseData != null) {
        _serviceDetailsModel = ServiceDetailsModel.fromJson(responseData);
        return true;
      }
    } catch (e) {
      debugPrint(e.toString());
      rethrow;
    } finally {
      _serviceDetailsModel ??= ServiceDetailsModel();
      notifyListeners();
    }
  }

  tryChangingPublishStatus() async {
    var url =
        "${AppUrls.changeServicePublishStatusUrl}/${serviceDetailsModel.allServices?.id}";
    var data = {};

    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.status, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        final previousStatus =
            (serviceDetailsModel.allServices?.isPublished ?? false);
        _serviceDetailsModel?.allServices?.isPublished =
            previousStatus ? false : true;
        LocalKeys.servicePublishStatusChangedSuccessfully.showToast();
      } catch (e) {
        debugPrint(e.toString());
      }
      notifyListeners();
      return true;
    }
  }

  reset() {
    _serviceDetailsModel = null;
  }

  tryDeletingService() async {
    var url =
        "${AppUrls.deleteServiceUrl}/${serviceDetailsModel.allServices?.id}";
    var data = {};

    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.delete, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        LocalKeys.serviceDeletedSuccessfully.showToast();
      } catch (e) {
        debugPrint(e.toString());
      }
      return true;
    }
  }
}
