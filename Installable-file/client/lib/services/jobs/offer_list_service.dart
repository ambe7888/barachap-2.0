import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/job/offer_list_model.dart';

class OfferListService with ChangeNotifier {
  OfferListModel? _offerListModel;
  OfferListModel get offerListModel =>
      _offerListModel ?? OfferListModel(jobOffers: []);

  String token = "";
  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  bool get shouldAutoFetch => _offerListModel == null || token.isInvalid;

  fetchOfferList(jobId, {bool refresh = false}) async {
    token = getToken;
    if (!refresh) {}
    var url = "${AppUrls.offerListUrl}?job_id=$jobId";
    debugPrint(url.toString());

    final responseData = await NetworkApiServices().getApi(
        url, LocalKeys.offers,
        headers: acceptJsonAuthHeader, timeoutSeconds: 30);

    try {
      if (responseData != null) {
        final tempData = OfferListModel.fromJson(responseData);
        _offerListModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
        return true;
      } else {}
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
        .getApi(nextPage, LocalKeys.jobs, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = OfferListModel.fromJson(responseData);
      for (var element in tempData.jobOffers) {
        _offerListModel?.jobOffers.add(element);
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
