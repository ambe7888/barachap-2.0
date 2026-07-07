import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../date_schedule_view/date_schedule_view.dart';
import '../../service_area_view/service_area_view.dart';
import '../../../view_models/onboarding_wizard_view_model/onboarding_wizard_view_model.dart';

class ScheduleStep extends StatelessWidget {
  const ScheduleStep({super.key});

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
                "Zone & Horaires",
                style: context.headlineMedium?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: context.color.primaryContrastColor,
                ),
              ),
              16.toHeight,
              Text(
                "Définissez la zone dans laquelle vous proposez vos services et vos disponibilités hebdomadaires.",
                style: context.bodyLarge?.copyWith(
                  color: context.color.secondaryContrastColor,
                ),
              ),
            ],
          ),
        ),
        Expanded(
          child: Consumer<ProfileInfoService>(
            builder: (context, profileService, child) {
              final userDetails = profileService.profileInfoModel.userDetails;
              final hasArea = userDetails?.serviceArea != null;

              return ListView(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                children: [
                  _buildNavTile(
                    context,
                    title: LocalKeys.serviceArea,
                    subtitle: hasArea ? "Zone configurée" : "Aucune zone configurée",
                    isDone: hasArea,
                    icon: Icons.map_outlined,
                    onTap: () async {
                      await Navigator.push(context, MaterialPageRoute(builder: (context) => const SignUpServiceArea(fromSettings: true)));
                      profileService.fetchProfileInfo();
                    },
                  ),
                  16.toHeight,
                  _buildNavTile(
                    context,
                    title: LocalKeys.scheduleAvailability,
                    subtitle: "Définissez vos jours et heures de travail",
                    isDone: true, // we don't have a strict check for empty schedules easily here
                    icon: Icons.calendar_month_outlined,
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (context) => const DateScheduleView()));
                    },
                  ),
                ],
              );
            },
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
              wizardViewModel.nextStep(context);
            },
            btText: "Étape suivante",
            isLoading: false,
          ),
        )
      ],
    );
  }

  Widget _buildNavTile(BuildContext context,
      {required String title,
      required String subtitle,
      required bool isDone,
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
                      style: context.bodySmall?.copyWith(
                          color: isDone ? Colors.green : context.color.primaryWarningColor)),
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
