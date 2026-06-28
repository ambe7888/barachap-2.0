import 'package:flutter/material.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/address_models/area_model.dart';

class AreaService with ChangeNotifier {
  bool areaLoading = false;
  String areaSearchText = '';
  dynamic areaId;

  List<Area> areaList = [];

  bool nextPageLoading = false;

  String? nextPage;

  bool nexLoadingFailed = false;

  setCitySearchValue(value) {
    if (value == areaSearchText) {
      return;
    }
    areaSearchText = value;
  }

  resetList(sId) {
    if (areaSearchText.isEmpty && areaList.isNotEmpty && sId == areaId) {
      return;
    }
    areaSearchText = '';
    areaList = [];
    areaId = sId;
    getCity();
  }

  void getCity() async {
    areaLoading = true;
    nextPage = null;
    notifyListeners();
    final url =
        "${AppUrls.areaUrl}?city_id=$areaId${areaSearchText.isEmpty ? "" : '&q=$areaSearchText'}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.area, headers: commonAuthHeader);

    try {
      if (responseData != null) {
        final tempData = AreaModel.fromJson(responseData);
        areaList = tempData.areas;
        nextPage = tempData.pagination?.nextPageUrl;
      } else {}
    } catch (e) {
      areaList = [];
    }

    areaLoading = false;
    notifyListeners();
  }

  fetchNextPage() async {
    if (nextPageLoading || nextPage == null) return;
    nextPageLoading = true;
    debugPrint("fetching dashboard info".toString());
    final responseData =
        await NetworkApiServices().getApi(nextPage!, LocalKeys.area);

    if (responseData != null) {
      final tempData = AreaModel.fromJson(responseData);
      for (var area in tempData.areas) {
        areaList.add(area);
      }
      nextPage = tempData.pagination?.nextPageUrl;
    } else {
      nexLoadingFailed = true;
      notifyListeners();
    }
    nextPageLoading = false;
    notifyListeners();
  }
}
