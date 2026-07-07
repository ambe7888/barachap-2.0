import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../onboarding_wizard_view/onboarding_wizard_view.dart';

class HomeOnboardingBanner extends StatelessWidget {
  const HomeOnboardingBanner({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<ProfileInfoService>(builder: (context, profileService, child) {
        final userDetails = profileService.profileInfoModel.userDetails;
        if (userDetails == null) return const SizedBox.shrink();

        final bool isPhoneMissing = userDetails.phone == null || userDetails.phone.toString().isEmpty;
        final bool isServiceAreaMissing = userDetails.serviceArea == null;
        final bool isServiceTypesMissing = userDetails.serviceTypes == null || userDetails.serviceTypes!.isEmpty;

        final bool isProfileIncomplete = isPhoneMissing || isServiceAreaMissing || isServiceTypesMissing;

        if (!isProfileIncomplete) return const SizedBox.shrink();

        return Container(
          width: context.width,
          margin: const EdgeInsets.only(bottom: 8),
          padding: const EdgeInsets.symmetric(
            horizontal: 24,
            vertical: 16,
          ),
          decoration: BoxDecoration(
            color: context.color.accentContrastColor,
          ),
          child: Column(
            children: [
              Icon(
                Icons.settings_suggest_outlined,
                size: 64,
                color: primaryColor,
              ),
              16.toHeight,
              Text(
                "Complétez votre profil",
                style: context.titleLarge?.bold,
                textAlign: TextAlign.center,
              ),
              4.toHeight,
              Text(
                "Veuillez configurer votre profil (numéro, zone, types de service) pour commencer à recevoir des demandes.",
                textAlign: TextAlign.center,
                style: context.bodyMedium?.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
              24.toHeight,
              CustomButton(
                  onPressed: () {
                    context.toPage(const OnboardingWizardView());
                  },
                  btText: "Configurer mon profil",
                  isLoading: false)
            ],
          ),
        );
      });
    });
  }
}
