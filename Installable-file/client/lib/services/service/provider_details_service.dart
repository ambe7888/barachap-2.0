import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/provider_details_model.dart';

class ProviderDetailsService with ChangeNotifier {
  final Map<String, ProviderDetailsModel> _providerDetailsModel = {};
  ProviderDetailsModel providerDetailsModel(id) =>
      _providerDetailsModel[id.toString()] ?? ProviderDetailsModel();

  String token = "";

  bool shouldAutoFetch(id) =>
      _providerDetailsModel[id.toString()]?.userDetails?.id.toString() != id ||
      token.isInvalid;

  fetchProviderDetails({required providerId, refresh = false}) async {
    token = getToken;
    final url = "${AppUrls.providerDetailsUrl}/${providerId.toString()}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.providerDetails, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _providerDetailsModel[providerId.toString()] =
          ProviderDetailsModel.fromJson(responseData);
    } else {}
    notifyListeners();
  }

  remove(id) {
    _providerDetailsModel.remove(id.toString());
  }
}
