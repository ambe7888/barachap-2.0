import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../data/network/network_api_services.dart';
import '../models/category_model.dart';

class CategoryService with ChangeNotifier {
  List<Category> categoryList = [];
  bool categoryLoading = false;
  String categorySearchText = '';

  bool nextPageLoading = false;

  String? nextPage;

  bool nexLoadingFailed = false;

  setCategorySearchValue(value) {
    if (value == categorySearchText) {
      return;
    }
    categorySearchText = value;
    debugPrint(categorySearchText.toString());
    fetchCategories();
  }

  resetList() {
    debugPrint(categorySearchText.toString());
    debugPrint("resetting list".toString());
    if (categorySearchText.isEmpty && categoryList.isNotEmpty) {
      debugPrint("resetting list skiped".toString());
      return;
    }
    debugPrint("resetting list in process".toString());
    categorySearchText = '';
    categoryList = [];
    fetchCategories();
  }

  fetchCategories() async {
    var url = "${AppUrls.categoryListUrl}?name=$categorySearchText";

    categoryLoading = true;
    notifyListeners();
    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.category);
    try {
      if (responseData != null) {
        final tempData = CategoryListModel.fromJson(responseData);

        categoryList = tempData.categories;
        nextPage = tempData.pagination?.nextPageUrl;
        return true;
      }
    } finally {
      categoryLoading = false;
      notifyListeners();
    }
  }

  fetchNextPage() async {
    var url = AppUrls.categoryListUrl;

    nextPageLoading = true;
    notifyListeners();
    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.category);
    try {
      if (responseData != null) {
        final tempData = CategoryListModel.fromJson(responseData);

        for (var cat in tempData.categories) {
          categoryList.add(cat);
        }
        nextPage = tempData.pagination?.nextPageUrl;
        return true;
      } else {
        nexLoadingFailed = true;
        Future.delayed(const Duration(seconds: 1)).then((value) {
          nexLoadingFailed = false;
          notifyListeners();
        });
      }
    } finally {
      nextPageLoading = false;
      notifyListeners();
    }
  }
}
