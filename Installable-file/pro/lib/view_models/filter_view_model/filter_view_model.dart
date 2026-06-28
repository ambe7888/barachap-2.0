import 'package:flutter/material.dart';
import 'package:prohand/models/service_models/service_unit_model.dart';

import '../../models/category_model.dart';

class FilterViewModel {
  TextEditingController searchController = TextEditingController();

  ValueNotifier<SearchType> selectedType = ValueNotifier(SearchType.service);
  ValueNotifier<Category?> selectedCategory = ValueNotifier(null);
  ValueNotifier<ServiceUnit?> selectedUnit = ValueNotifier(null);
  ValueNotifier<double?> ratingCount = ValueNotifier(null);
  ValueNotifier<RangeValues> priceRange =
      ValueNotifier(const RangeValues(0, 3000));

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
}

enum SearchType { service, provider }
