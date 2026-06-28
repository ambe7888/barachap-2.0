import 'package:flutter/material.dart';
import 'package:prohand/services/category_service.dart';
import 'package:prohand/view_models/category_view_model/category_view_model.dart';
import 'package:provider/provider.dart';

import '../../category_view/components/category_card.dart';
import 'sub_category_sheet.dart';

class CategoryViewList extends StatelessWidget {
  final CategoryViewModel cvm;
  const CategoryViewList({super.key, required this.cvm});

  @override
  Widget build(BuildContext context) {
    return Consumer<CategoryService>(builder: (context, cat, child) {
      return ValueListenableBuilder(
        valueListenable: cvm.selectedCategory,
        builder: (context, category, child) {
          return Wrap(
            spacing: 12,
            runSpacing: 24,
            alignment: WrapAlignment.center,
            children: cat.categoryList
                .map((e) => GestureDetector(
                      onTap: () {
                        cvm.selectedCategory.value = e;
                        showModalBottomSheet(
                          context: context,
                          isScrollControlled: true,
                          builder: (context) {
                            return SubCategorySheet(
                                controller: ScrollController(),
                                subCatNotifier: ValueNotifier(null));
                          },
                        );
                      },
                      child: CategoryCard(
                        category: e,
                        isSelected: category?.id.toString() == e.id.toString(),
                      ),
                    ))
                .toList(),
          );
        },
      );
    });
  }
}
