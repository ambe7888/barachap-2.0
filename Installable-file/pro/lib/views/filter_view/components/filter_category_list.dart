
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/view_models/filter_view_model/filter_view_model.dart';
import 'package:prohand/views/categories_view/categories_view.dart';
import 'package:provider/provider.dart';

import '../../../services/filter_category_list_service.dart';
import '../../../utils/components/label_with_see_all.dart';
import '../../category_view/components/category_card.dart';

class FilterCategoryList extends StatelessWidget {
  const FilterCategoryList({super.key});

  @override
  Widget build(BuildContext context) {
    final fvm = FilterViewModel.instance;
    return ChangeNotifierProvider(
      create: (_) => FilterCategoryListService(),
      child: Column(
        children: [
          LabelWithSeeAll(
              label: LocalKeys.categories,
              onPressed: () {
                context.toPage(const CategoriesView());
              }),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(
              horizontal: 24,
            ),
            child: Consumer<FilterCategoryListService>(
                builder: (context, fcl, child) {
              return ValueListenableBuilder(
                valueListenable: fvm.selectedCategory,
                builder: (context, category, child) {
                  return Wrap(
                    spacing: 8,
                    children: fcl.categoryList
                        .map((e) => GestureDetector(
                              onTap: () {
                                if (category?.id.toString() ==
                                    e.id.toString()) {
                                  fvm.selectedCategory.value = null;
                                  return;
                                }
                                fvm.selectedCategory.value = e;
                              },
                              child: CategoryCard(
                                category: e,
                                isSelected:
                                    category?.id.toString() == e.id.toString(),
                              ),
                            ))
                        .toList(),
                  );
                },
              );
            }),
          ),
        ],
      ),
    );
  }
}
