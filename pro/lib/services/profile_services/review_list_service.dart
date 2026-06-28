import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/profile_models/review_list_model.dart';

class ReviewListService with ChangeNotifier {
  ReviewListModel? _reviewListModel;
  ReviewListModel get reviewListModel =>
      _reviewListModel ?? ReviewListModel(reviews: []);

  String token = "";
  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  bool get shouldAutoFetch => _reviewListModel == null || token.isInvalid;

  fetchReviewList() async {
    token = getToken;
    var url = AppUrls.reviewListUrl;

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.myServices, headers: acceptJsonAuthHeader);

    try {
      if (responseData != null) {
        final tempData = ReviewListModel.fromJson(responseData);
        _reviewListModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
        return true;
      } else {
        _reviewListModel ??= ReviewListModel(reviews: []);
      }
    } catch (e) {
      debugPrint(e.toString());
      rethrow;
    } finally {
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
      final tempData = ReviewListModel.fromJson(responseData);
      for (var element in tempData.reviews) {
        _reviewListModel?.reviews.add(element);
      }
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
