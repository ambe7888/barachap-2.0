import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:prohandy_client/models/provider_model.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';

class HomeProvidersService with ChangeNotifier {
  List<ProviderModel>? providerList;

  initLocal() {
    final localData = sPref?.getString("providers");
    final tempData = ProviderListModel.fromJson(jsonDecode(localData ?? "{}"));
    if ((providerList ?? []).isNotEmpty) {
      providerList = tempData.providerLists;
      fetchHomeProviders();
    }
  }

  fetchHomeProviders() async {
    var url = AppUrls.homeProvidersListUrl;

    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.providers);
    try {
      if (responseData != null) {
        final tempData = ProviderListModel.fromJson(responseData);

        providerList = tempData.providerLists ?? [];
        sPref?.setString("providers", jsonEncode(responseData));
      }
    } finally {
      providerList ??= [];
      notifyListeners();
    }
  }
}
