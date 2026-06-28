import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';

import '../../../app_static_values.dart';
import '../../../models/provider_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class HomeProviderCardImageRating extends StatelessWidget {
  const HomeProviderCardImageRating({
    super.key,
    required this.provider,
  });

  final ProviderModel provider;

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: (context.width * 0.17) + 8,
      child: Stack(
        children: [
          Align(
            alignment: Alignment.topCenter,
            child: CustomNetworkImage(
              height: context.width * 0.17,
              width: context.width * 0.17,
              radius: (context.width * 0.17) / 2,
              fit: BoxFit.cover,
              imageUrl: provider.image,
              name: provider.name,
              color: chatAvatarBGColors[(int.tryParse(provider.id.toString()) ??
                      Random().nextInt(1632)) %
                  chatAvatarBGColors.length],
              userPreloader: true,
            ),
          ),
          Align(
            alignment: Alignment.bottomCenter,
            child: SquircleContainer(
              color: context.color.accentContrastColor,
              radius: 8,
              child: SquircleContainer(
                  padding:
                      const EdgeInsets.symmetric(vertical: 4, horizontal: 6),
                  color: context.color.primaryPendingColor.withOpacity(.2),
                  radius: 8,
                  child: FittedBox(
                    child: Row(
                      children: [
                        Icon(
                          Icons.star_rounded,
                          size: 18,
                          color: context.color.primaryPendingColor,
                        ),
                        4.toWidth,
                        Text(
                          provider.avgRating.toStringAsFixed(1),
                          style: context.bodySmall
                              ?.copyWith(
                                color: context.color.primaryContrastColor,
                              )
                              .bold5,
                        ),
                      ],
                    ),
                  )),
            ),
          )
        ],
      ),
    );
  }
}
