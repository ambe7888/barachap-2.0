import 'package:flutter/material.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';

class HomeProvidersService with ChangeNotifier {
  fetchHomeProviders() async {
    var url = AppUrls.areaUrl;

    final responseData = await NetworkApiServices().getApi(url, null);

    if (responseData != null) {
      return true;
    }
  }
}
