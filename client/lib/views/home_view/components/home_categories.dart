import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/views/category_view/components/category_card.dart';
import 'package:provider/provider.dart';

import '../../../services/home_services/home_category_service.dart';
import '../../service_by_category_view/service_by_category_view.dart';
import 'category_card_skeleton.dart';

class HomeCategories extends StatelessWidget {
  const HomeCategories({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<HomeCategoryService>(builder: (context, hc, child) {
      return FutureBuilder(
          future: hc.categoryList == null ? hc.fetchCategories() : null,
          builder: (context, snap) {
            if (snap.connectionState == ConnectionState.waiting) {
              return Container(
                color: context.color.accentContrastColor,
                child: SingleChildScrollView(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
                  scrollDirection: Axis.horizontal,
                  physics: const NeverScrollableScrollPhysics(),
                  child: Wrap(
                    spacing: 10,
                    children: [1, 3, 6, 7, 9, 5].map((cat) {
                      return const CategoryCardSkeleton();
                    }).toList(),
                  ),
                ),
              );
            }
            if ((hc.categoryList ?? []).isEmpty) {
              return const SizedBox();
            }
            return Container(
              color: context.color.accentContrastColor,
              child: SingleChildScrollView(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
                scrollDirection: Axis.horizontal,
                child: Wrap(
                  spacing: 10,
                  children: hc.categoryList!.map((cat) {
                    return GestureDetector(
                      onTap: () {
                        context.toPage(ServiceByCategoryView(catId: cat.id, catName: cat.name));
                      },
                      child: CategoryCard(
                        category: cat,
                      ),
                    );
                  }).toList(),
                ),
              ),
            );
          });
    });
  }
}
