import 'dart:async';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/service/service_unit_model.dart';
import 'package:prohandy_client/services/service/services_search_service.dart';
import 'package:provider/provider.dart';

import '../../models/category_model.dart';

class FilterViewModel {
  TextEditingController searchController = TextEditingController();

  ValueNotifier<SearchType> selectedType = ValueNotifier(SearchType.service);
  ValueNotifier<Category?> selectedCategory = ValueNotifier(null);
  ValueNotifier<ServiceUnit?> selectedUnit = ValueNotifier(null);
  ValueNotifier<double?> ratingCount = ValueNotifier(null);
  ValueNotifier<RangeValues> priceRange =
      ValueNotifier(const RangeValues(0, 3000));

  Timer? timer;

  FilterViewModel._init();
  static FilterViewModel? _instance;
  static FilterViewModel get instance {
    _instance ??= FilterViewModel._init();
    return _instance!;
  }

  FilterViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  initFilters(BuildContext context) {
    final ss = Provider.of<ServicesSearchService>(context, listen: false);
    selectedCategory.value = ss.selectedCategory;
    searchController.text = ss.title ?? "";
    ratingCount.value = ss.ratingCount;
    priceRange.value =
        RangeValues(ss.minPrice?.toDouble() ?? 0, ss.maxPrice?.toDouble() ?? 0);
  }

  setFilters(BuildContext context) {
    final ss = Provider.of<ServicesSearchService>(context, listen: false);

    ss.setFilters(
      selectedCategory: selectedCategory.value,
      minPrice: priceRange.value.start.toString().tryToParse,
      maxPrice: priceRange.value.end.toString().tryToParse,
      ratingCount: ratingCount.value,
    );
  }

  void reset(BuildContext context) {
    final ss = Provider.of<ServicesSearchService>(context, listen: false);

    ss.setFilters();
  }
}

enum SearchType { service, provider }
