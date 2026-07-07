import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:provider/provider.dart';

import '../../view_models/onboarding_wizard_view_model/onboarding_wizard_view_model.dart';
import 'steps/categories_step.dart';
import 'steps/phone_step.dart';
import 'steps/schedule_step.dart';
import 'steps/staff_step.dart';

class OnboardingWizardView extends StatelessWidget {
  const OnboardingWizardView({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => OnboardingWizardViewModel(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        body: SafeArea(
          child: Consumer<OnboardingWizardViewModel>(
            builder: (context, viewModel, child) {
              return Column(
                children: [
                  _buildHeader(context, viewModel),
                  Expanded(
                    child: PageView(
                      controller: viewModel.pageController,
                      physics: const NeverScrollableScrollPhysics(), // User must use buttons to navigate
                      onPageChanged: viewModel.onPageChanged,
                      children: const [
                        PhoneStep(),
                        CategoriesStep(),
                        ScheduleStep(),
                        StaffStep(),
                      ],
                    ),
                  ),
                ],
              );
            },
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context, OnboardingWizardViewModel viewModel) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          // Back Button or Empty Space
          viewModel.currentIndex > 0
              ? IconButton(
                  icon: const Icon(Icons.arrow_back_ios, size: 20),
                  onPressed: viewModel.previousStep,
                )
              : const SizedBox(width: 48), // Placeholder for alignment

          // Progress Dots
          Row(
            children: List.generate(
              viewModel.totalSteps,
              (index) => Container(
                margin: const EdgeInsets.symmetric(horizontal: 4),
                width: viewModel.currentIndex == index ? 24 : 8,
                height: 8,
                decoration: BoxDecoration(
                  color: viewModel.currentIndex == index
                      ? primaryColor
                      : context.color.primaryBorderColor,
                  borderRadius: BorderRadius.circular(4),
                ),
              ),
            ),
          ),

          // Skip Button
          TextButton(
            onPressed: () => viewModel.nextStep(context),
            child: Text(
              "Passer",
              style: context.bodyMedium?.copyWith(
                color: context.color.secondaryContrastColor,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
