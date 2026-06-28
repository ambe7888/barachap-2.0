import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/category_model.dart';

class HomeCategoryService with ChangeNotifier {
  CategoryListModel? _categoryListModel;
  CategoryListModel get categoryListModel =>
      _categoryListModel ?? CategoryListModel(categories: []);
  List<Category> categoryList = [];

  bool get shouldAutoFetch => _categoryListModel == null;

  fetchCategories() async {
    var url = AppUrls.categoryListUrl;

    final responseData =
        await NetworkApiServices().getApi(url, LocalKeys.category);

    if (responseData != null) {
      _categoryListModel = CategoryListModel.fromJson(responseData);
      categoryList = categoryListModel.categories;
    } else {}
    notifyListeners();
  }
}
