import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/home_services/home_category_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';
import 'package:provider/provider.dart';

import '../../category_view/components/category_card.dart';
import '../../../view_models/onboarding_wizard_view_model/onboarding_wizard_view_model.dart';

class CategoriesStep extends StatelessWidget {
  const CategoriesStep({super.key});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    final hcMessages = Provider.of<HomeCategoryService>(context, listen: false);
    final wizardViewModel = Provider.of<OnboardingWizardViewModel>(context, listen: false);

    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                LocalKeys.yourServiceTypes,
                style: context.headlineMedium?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: context.color.primaryContrastColor,
                ),
              ),
              16.toHeight,
              Text(
                LocalKeys.yourServiceTypesDesc,
                style: context.bodyLarge?.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
            ],
          ),
        ),
        Expanded(
          child: CustomFutureWidget(
            function: hcMessages.shouldAutoFetch
                ? hcMessages.fetchCategories()
                : null,
            shimmer: const CustomPreloader(),
            child: Consumer<HomeCategoryService>(
              builder: (context, hc, child) {
                if (hc.categoryListModel.categories.isEmpty) {
                  return const Center(child: Text("Aucune catégorie"));
                }
                final items = hc.categoryListModel.categories;
                return GridView.builder(
                  padding: const EdgeInsets.symmetric(horizontal: 24),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 3,
                    childAspectRatio: .9,
                    crossAxisSpacing: 16,
                    mainAxisSpacing: 16,
                  ),
                  itemCount: items.length,
                  itemBuilder: (context, index) {
                    final item = items[index];
                    return ValueListenableBuilder(
                        valueListenable: pem.categories,
                        builder: (context, categories, child) {
                          bool isSelected = categories.contains(item.id.toString());
                          return GestureDetector(
                            onTap: () {
                              pem.addRemoveCategory(item.id.toString());
                            },
                            child: CategoryCard(
                              category: item,
                              isSelected: isSelected,
                            ),
                          );
                        });
                  },
                );
              },
            ),
          ),
        ),
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
              top: BorderSide(color: context.color.primaryBorderColor),
            ),
          ),
          child: ValueListenableBuilder<bool>(
            valueListenable: pem.isLoading,
            builder: (context, val, child) => CustomButton(
              onPressed: () async {
                if (pem.categories.value.isEmpty) {
                  "Veuillez sélectionner au moins une catégorie".showToast();
                  return;
                }
                pem.updateServiceType(context);
                wizardViewModel.nextStep(context);
              },
              btText: "Valider les catégories",
              isLoading: val,
            ),
          ),
        )
      ],
    );
  }
}
