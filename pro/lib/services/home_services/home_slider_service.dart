import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/local_keys.g.dart';
import '../../models/slider_model.dart';

class HomeSliderService with ChangeNotifier {
  List<HomeSliderModel> sliderList = [];

  fetchHomeSlider() async {
    var url = AppUrls.areaUrl;

    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.sliders);

    if (responseData != null) {
      return true;
    }
  }
}
