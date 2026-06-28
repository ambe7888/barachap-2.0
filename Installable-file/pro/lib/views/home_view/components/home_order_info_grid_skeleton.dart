import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../services/theme_service.dart';
import 'order_grid_slot_skeleton.dart';

class HomeOrderInfoGridSkeleton extends StatelessWidget {
  const HomeOrderInfoGridSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Container(
              color: context.color.accentContrastColor,
              padding: 24.paddingH,
              child: Wrap(
                spacing: 12,
                runSpacing: 12,
                children: [
                  Row(
                    children: [8.toHeight],
                  ),
                  OrderGridSlotSkeleton(
                    borderColor: gridColors[0],
                    color: gridColors[0].withOpacity(.07),
                  ),
                  OrderGridSlotSkeleton(
                    borderColor: gridColors[1],
                    color: gridColors[1].withOpacity(.07),
                  ),
                  OrderGridSlotSkeleton(
                    borderColor: gridColors[2],
                    color: gridColors[2].withOpacity(.07),
                  ),
                  OrderGridSlotSkeleton(
                    borderColor: gridColors[3],
                    color: gridColors[3].withOpacity(.07),
                  ),
                  Row(
                    children: [8.toHeight],
                  )
                ],
              ))
          .animate(
            delay: 0.ms,
            autoPlay: true,
            onPlay: (controller) => controller.repeat(),
          )
          .shimmer(
            duration: const Duration(seconds: 1),
            color: ts.selectedTheme.accentContrastColor.withOpacity(.65),
          );
    });
  }
}
