import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../app_static_values.dart';
import '../../../customizations/colors.dart';
import '../../../utils/components/custom_network_image.dart';

class OrderTileStaffs extends StatelessWidget {
  const OrderTileStaffs({super.key});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        ...List.generate(
          7,
          (index) => Container(
            margin: EdgeInsets.only(
                left: context.dProvider.textDirectionRight
                    ? 0
                    : (30 * index).toDouble(),
                right: context.dProvider.textDirectionRight
                    ? (30 * index).toDouble()
                    : 0),
            decoration: BoxDecoration(
                shape: BoxShape.circle,
                border:
                    Border.all(color: context.color.cardFillColor, width: 4)),
            child: CustomNetworkImage(
              height: 36,
              width: 36,
              fit: BoxFit.cover,
              radius: 16,
              imageUrl: "",
              name: "Robert",
              color: chatAvatarBGColors[
                  (int.tryParse(index.toString()) ?? Random().nextInt(1632)) %
                      chatAvatarBGColors.length],
              userPreloader: true,
            ),
          ),
        ),
        if (1 > 0)
          Container(
            height: 40,
            width: 40,
            margin: EdgeInsets.only(
                left: context.dProvider.textDirectionRight
                    ? 0
                    : (30 * 7).toDouble(),
                right: context.dProvider.textDirectionRight
                    ? (30 * 7).toDouble()
                    : 0),
            padding: const EdgeInsets.all(2),
            alignment: Alignment.center,
            decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: primaryColor,
                border: Border.all(
                    color: context.color.cardFillColor,
                    width: 4,
                    strokeAlign: BorderSide.strokeAlignCenter)),
            child: Text(
              "3+",
              style: context.titleMedium?.bold
                  .copyWith(color: context.color.accentContrastColor),
            ),
          )
      ],
    );
  }
}
