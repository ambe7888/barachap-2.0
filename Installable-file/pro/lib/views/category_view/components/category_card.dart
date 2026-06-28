import 'package:badges/badges.dart' as badge;
import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../customizations/colors.dart';
import '../../../models/category_model.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/marquee.dart';

class CategoryCard extends StatelessWidget {
  final Category category;
  final bool isSelected;
  const CategoryCard({
    super.key,
    required this.category,
    this.isSelected = false,
  });

  @override
  Widget build(BuildContext context) {
    return badge.Badge(
      showBadge: isSelected,
      badgeStyle: const badge.BadgeStyle(badgeColor: primaryColor),
      position: badge.BadgePosition.topEnd(top: -2, end: -2),
      badgeContent: Icon(
        Icons.done_rounded,
        color: context.color.accentContrastColor,
        size: 12,
      ),
      child: SizedBox(
        height: context.width * 0.235,
        width: 64,
        child: Column(
          children: [
            Container(
              height: 64,
              width: 64,
              padding: const EdgeInsets.all(14),
              decoration: ShapeDecoration(
                shape: SmoothRectangleBorder(
                    borderRadius: SmoothBorderRadius(
                      cornerRadius: 16,
                      cornerSmoothing: 0.5,
                    ),
                    side: BorderSide(
                      color: isSelected
                          ? primaryColor
                          : context.color.primaryBorderColor,
                    )),
              ),
              child: CustomNetworkImage(
                imageUrl: category.image.toString(),
              ),
            ),
            8.toHeight,
            Marquee(
              directionMarguee: DirectionMarguee.TwoDirection,
              child: Text(
                category.name ?? "---",
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: context.bodySmall?.bold,
              ),
            )
          ],
        ),
      ),
    );
  }
}
