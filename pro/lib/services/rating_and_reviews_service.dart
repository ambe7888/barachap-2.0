import 'package:flutter/material.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';

class RatingAndReviewsService with ChangeNotifier {
  var _ratingAndReviewsModel;
  Object get ratingAndReviewsModel => _ratingAndReviewsModel;
  var token = "";
  bool isLoading = false;

  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _ratingAndReviewsModel == null || token.isInvalid;

  fetchRatingAndReviewsList({refreshing = false}) async {
    debugPrint("trying to fetch job list".toString());
    token = getToken;
    if (!refreshing) {
      debugPrint("not refreshing".toString());
      isLoading = true;
      notifyListeners();
    }
    debugPrint("fetching dashboard info".toString());
    final url = AppUrls.ratingAndReviewsUrl;
    final responseData = await NetworkApiServices()
        .postApi({}, url, LocalKeys.jobList, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _ratingAndReviewsModel = [];
    } else {
      _ratingAndReviewsModel ??= [];
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
}
