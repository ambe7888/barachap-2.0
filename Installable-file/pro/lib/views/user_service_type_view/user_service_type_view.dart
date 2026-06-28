import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../services/home_services/home_category_service.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_preloader.dart';
import '../category_view/components/category_card.dart';
import 'components/user_service_type_button.dart';

class UserServiceTypeView extends StatelessWidget {
  const UserServiceTypeView({super.key});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    final hcMessages = Provider.of<HomeCategoryService>(context, listen: false);
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await hcMessages.fetchCategories();
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Divider(
                height: 8,
                thickness: 8,
                color: context.color.backgroundColor,
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      LocalKeys.yourServiceTypes,
                      style: context.labelLarge?.bold,
                    ),
                    4.toHeight,
                    Text(
                      LocalKeys.yourServiceTypesDesc,
                      style: context.bodyMedium,
                    ),
                    32.toHeight,
                    Center(
                      child: CustomFutureWidget(
                        function: hcMessages.shouldAutoFetch
                            ? hcMessages.fetchCategories()
                            : null,
                        shimmer: const CustomPreloader(),
                        child: Consumer<HomeCategoryService>(
                            builder: (context, hc, child) {
                          return Builder(builder: (context) {
                            debugPrint(hc.categoryList.toString());
                            debugPrint(
                                hc.categoryListModel.categories.toString());
                            return ValueListenableBuilder(
                                valueListenable: pem.categories,
                                builder: (context, categories, child) {
                                  return Wrap(
                                    spacing: 16,
                                    runSpacing: 16,
                                    children: hc.categoryListModel.categories
                                        .map((cat) {
                                      return GestureDetector(
                                        onTap: () {
                                          pem.addRemoveCategory(
                                              cat.id.toString());
                                        },
                                        child: CategoryCard(
                                          category: cat,
                                          isSelected: categories
                                              .contains(cat.id.toString()),
                                        ),
                                      );
                                    }).toList(),
                                  );
                                });
                          });
                        }),
                      ),
                    ),
                  ],
                ),
              )
            ],
          ),
        ),
      ),
      bottomNavigationBar: const UserServiceTypeButton(),
    );
  }
}
