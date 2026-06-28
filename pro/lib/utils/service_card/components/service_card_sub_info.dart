import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/utils/components/marquee.dart';

import '../../../helper/local_keys.g.dart';

class ServiceCardSubInfo extends StatelessWidget {
  final num avgRating;
  final num soldCount;
  final String unit;
  final String? category;
  const ServiceCardSubInfo(
      {super.key,
      required this.avgRating,
      required this.unit,
      this.category,
      this.soldCount = 0});

  @override
  Widget build(BuildContext context) {
    return Marquee(
        animationDuration: 2000.milliseconds,
        backDuration: 2000.milliseconds,
        child: Row(
          children: [
            if (avgRating > 0) ...[
              Icon(
                Icons.star_rounded,
                color: context.color.primaryPendingColor,
                size: 24,
              ),
            ],
            RichText(
                text: TextSpan(
                    text: null,
                    style: context.bodySmall?.copyWith(
                      color: context.color.tertiaryContrastColo,
                    ),
                    children: [
                  if ((avgRating > 0))
                    TextSpan(
                      text: "${avgRating.toStringAsFixed(1)} . ",
                      style: context.bodyMedium,
                    ),
                  if ((unit ?? "").isNotEmpty)
                    TextSpan(
                      text: "${unit.capitalize} . ",
                    ),
                  if (category != null)
                    TextSpan(
                      text: category!.capitalize,
                    ),
                  if (soldCount > 0)
                    TextSpan(
                      text: " . ${formatNumber(soldCount)} ${LocalKeys.sold}",
                    ),
                ]))
          ],
        ));
  }

  String formatNumber(num number) {
    if (number >= 1000 && number < 1000000) {
      return "${(number / 1000).toStringAsFixed(1)}k";
    } else if (number >= 1000000) {
      return "${(number / 1000000).toStringAsFixed(1)}M";
    } else {
      return number.toString();
    }
  }
}
