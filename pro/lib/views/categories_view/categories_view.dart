import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/services/category_service.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/category_view_model/category_view_model.dart';
import 'package:provider/provider.dart';

import 'components/categorie_view_list.dart';

class CategoriesView extends StatelessWidget {
  const CategoriesView({super.key});

  @override
  Widget build(BuildContext context) {
    final cvm = CategoryViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => CategoryService(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.categories),
        ),
        body: Scrollbar(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(LocalKeys.searchOrChoseCategory,
                    style: context.headlineLarge?.bold6),
                16.toHeight,
                TextFormField(
                  decoration: InputDecoration(
                      hintText: LocalKeys.searchCategory,
                      prefixIcon: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        child: SvgAssets.search.toSVGSized(
                          24,
                          color: context.color.secondaryContrastColor,
                        ),
                      )),
                ),
                32.toHeight,
                CategoryViewList(
                  cvm: cvm,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
