import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../my_staffs_list_view/my_staffs_list_view.dart';
import '../../../view_models/onboarding_wizard_view_model/onboarding_wizard_view_model.dart';

class StaffStep extends StatelessWidget {
  const StaffStep({super.key});

  @override
  Widget build(BuildContext context) {
    final wizardViewModel = Provider.of<OnboardingWizardViewModel>(context, listen: false);

    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Équipe (Optionnel)",
                style: context.headlineMedium?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: context.color.primaryContrastColor,
                ),
              ),
              16.toHeight,
              Text(
                "Vous travaillez avec d'autres personnes ? Ajoutez-les à votre équipe pour mieux gérer vos missions.",
                style: context.bodyLarge?.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
            ],
          ),
        ),
        Expanded(
          child: ListView(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            children: [
              _buildNavTile(
                context,
                title: "Mon équipe",
                subtitle: "Gérer les membres de votre équipe",
                icon: Icons.people_alt_outlined,
                onTap: () {
                  Navigator.push(context, MaterialPageRoute(builder: (context) => MyStaffsListView()));
                },
              ),
            ],
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
          child: CustomButton(
            onPressed: () {
              wizardViewModel.nextStep(context); // Will finish the wizard
            },
            btText: "Terminer et accéder à l'accueil",
            isLoading: false,
            backgroundColor: primaryColor,
          ),
        )
      ],
    );
  }

  Widget _buildNavTile(BuildContext context,
      {required String title,
      required String subtitle,
      required IconData icon,
      required VoidCallback onTap}) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          border: Border.all(color: context.color.primaryBorderColor),
          borderRadius: BorderRadius.circular(12),
          color: context.color.backgroundColor,
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: primaryColor.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: primaryColor),
            ),
            16.toWidth,
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: context.titleMedium?.bold),
                  4.toHeight,
                  Text(subtitle,
                      style: context.bodySmall?.copyWith(color: context.color.secondaryContrastColor)),
                ],
              ),
            ),
            const Icon(Icons.arrow_forward_ios, size: 16),
          ],
        ),
      ),
    );
  }
}
