import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../identity_verify_view/identity_verify_view.dart';

class HomeOnboardingBanner extends StatelessWidget {
  const HomeOnboardingBanner({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<ProfileInfoService>(builder: (context, profileService, child) {
        final userDetails = profileService.profileInfoModel.userDetails;
        if (userDetails == null) return const SizedBox.shrink();

        final isVerified = userDetails.verifiedStatus == "1";

        if (isVerified) return const SizedBox.shrink();

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
                Icons.verified_user_outlined,
                size: 64,
                color: context.color.primaryWarningColor,
              ),
              16.toHeight,
              Text(
                "Vérification de Compte",
                style: context.titleLarge?.bold,
                textAlign: TextAlign.center,
              ),
              4.toHeight,
              Text(
                "Votre compte n'est pas encore vérifié. Veuillez soumettre vos informations pour obtenir le badge de vérification.",
                textAlign: TextAlign.center,
                style: context.bodyMedium?.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
              24.toHeight,
              CustomButton(
                  onPressed: () {
                    context.toPage(const IdentityVerifyView());
                  },
                  btText: "Vérifier le profil",
                  isLoading: false)
            ],
          ),
        );
      });
    });
  }
}
