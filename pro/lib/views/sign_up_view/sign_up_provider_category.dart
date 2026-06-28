import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/home_services/home_category_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/view_models/sign_up_view_model/sign_up_view_model.dart';
import 'package:prohand/views/landing_view/landing_view.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../helper/svg_assets.dart';
import '../../utils/components/alerts.dart';
import '../category_view/components/category_card.dart';

class SignUpProviderCategory extends StatelessWidget {
  const SignUpProviderCategory({super.key});

  @override
  Widget build(BuildContext context) {
    final sum = SignUpViewModel.instance;
    final hcMessages = Provider.of<HomeCategoryService>(context, listen: false);
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const SizedBox(),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
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
                  return ValueListenableBuilder(
                      valueListenable: sum.categories,
                      builder: (context, categories, child) {
                        return Wrap(
                          spacing: 16,
                          runSpacing: 16,
                          children: hc.categoryList.map((cat) {
                            return GestureDetector(
                              onTap: () {
                                sum.addRemoveCategory(cat.id.toString());
                              },
                              child: CategoryCard(
                                category: cat,
                                isSelected:
                                    categories.contains(cat.id.toString()),
                              ),
                            );
                          }).toList(),
                        );
                      });
                }),
              ),
            ),
            32.toHeight,
            CustomButton(
              onPressed: () {
                sum.tryToSetProfileInfo(context);
                Alerts().showInfoDialogue(
                    context: context,
                    title: LocalKeys.congrats,
                    description: LocalKeys.youHaveSignedUpSuccessfully,
                    infoAsset: SvgAssets.addFilled.toSVGSized(
                      100,
                      color: context.color.primarySuccessColor,
                    ));
                context.toUntilPage(const LandingView());
              },
              btText: LocalKeys.continueO,
            ),
          ],
        ),
      ),
    );
  }
}
