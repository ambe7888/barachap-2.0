import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:provider/provider.dart';

import '../../../services/theme_service.dart';
import 'home_assigned_staffs_skeleton.dart';
import 'home_order_in_progress_skeleton.dart';

class HomeTodaysOrdersSkeleton extends StatelessWidget {
  const HomeTodaysOrdersSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          8.toHeight,
          const HomeOrderInProgressSkeleton()
              .animate(
                delay: 0.ms,
                autoPlay: true,
                onPlay: (controller) => controller.repeat(),
              )
              .shimmer(
                duration: const Duration(seconds: 1),
                color: ts.selectedTheme.accentContrastColor.withOpacity(.65),
              ),
          8.toHeight,
          const HomeAssignedStaffsSkeleton()
              .animate(
                delay: 0.ms,
                autoPlay: true,
                onPlay: (controller) => controller.repeat(),
              )
              .shimmer(
                duration: const Duration(seconds: 1),
                color: ts.selectedTheme.accentContrastColor.withOpacity(.65),
              )
        ],
      );
    });
  }
}
