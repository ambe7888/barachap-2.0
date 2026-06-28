import 'package:flutter/material.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';

class JobListService with ChangeNotifier {
  var _jobListModel;
  Object get jobListModel => _jobListModel;
  var token = "";
  bool isLoading = false;

  String? length;
  String? maxPrice;
  String? minPrice;
  String searchText = "";

  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _jobListModel == null || token.isInvalid;

  fetchJobList({refreshing = false}) async {
    debugPrint("trying to fetch job list".toString());
    token = getToken;
    if (!refreshing) {
      debugPrint("not refreshing".toString());
      isLoading = true;
      notifyListeners();
    }

    final url = AppUrls.jobList;
    final responseData = await NetworkApiServices()
        .postApi({}, url, LocalKeys.jobList, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _jobListModel = [];
    } else {
      _jobListModel ??= [];
    }
    isLoading = false;
    notifyListeners();
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    final responseData = await NetworkApiServices()
        .postApi({}, nextPage, LocalKeys.jobList, headers: commonAuthHeader);

    if (responseData != null) {
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

  void setSearchText(String value) {
    searchText = value;
  }
}
