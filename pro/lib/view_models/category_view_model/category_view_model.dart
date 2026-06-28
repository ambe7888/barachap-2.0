import 'package:flutter/material.dart';
import 'package:prohand/models/category_model.dart';

class CategoryViewModel {
  ScrollController scrollController = ScrollController();

  final ValueNotifier<Category?> selectedCategory = ValueNotifier(null);

  CategoryViewModel._init();
  static CategoryViewModel? _instance;
  static CategoryViewModel get instance {
    _instance ??= CategoryViewModel._init();
    return _instance!;
  }

  CategoryViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
