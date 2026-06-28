import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/category_model.dart';

import '../../data/network/network_api_services.dart';
import '../../models/home_models/services_list_model.dart';

class ServicesSearchService with ChangeNotifier {
  ServiceListModel? _searchResultModel;
  ServiceListModel get searchResultModel =>
      _searchResultModel ?? ServiceListModel(allServices: []);

  Category? selectedCategory;
  num? minPrice;
  num? maxPrice;
  double? ratingCount;
  String? title;

  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  String get getFilter {
    String param = "?title=${title ?? ""}";
    if (selectedCategory?.id != null) {
      param = "$param&cat_id=${selectedCategory!.id}";
    }
    if ((minPrice ?? 0) > 0) {
      param = "$param&min_price=$minPrice";
    }
    if ((maxPrice ?? 0) > 0) {
      param = "$param&max_price=$maxPrice";
    }
    if ((ratingCount ?? 0) > 0) {
      param = "$param&rating=${ratingCount!.toInt()}";
    }
    return param;
  }

  setFilters({
    Category? selectedCategory,
    num? minPrice,
    num? maxPrice,
    double? ratingCount,
  }) {
    this.selectedCategory = selectedCategory;
    this.minPrice = minPrice;
    this.maxPrice = maxPrice;
    this.ratingCount = ratingCount;

    fetchHomeFeaturedServices();
  }

  bool get shouldAutoFetch => _searchResultModel?.allServices == null;

  fetchHomeFeaturedServices({refreshing = false}) async {
    var url = "${AppUrls.serviceListUrl}$getFilter";
    if (!refreshing) {
      isLoading = true;
      notifyListeners();
    }
    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.searchService);

    try {
      if (responseData != null) {
        final tempData = ServiceListModel.fromJson(responseData);
        _searchResultModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
      } else {
        _searchResultModel ??= ServiceListModel(allServices: []);
      }
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  fetchNextPage() async {
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData =
        await NetworkApiServices().getApi(nextPage, LocalKeys.jobs);

    if (responseData != null) {
      final tempData = ServiceListModel.fromJson(responseData);
      for (var element in tempData.allServices) {
        _searchResultModel?.allServices.add(element);
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

  void setSearchTitle(String text) {
    title = text;

    fetchHomeFeaturedServices();
  }
}
