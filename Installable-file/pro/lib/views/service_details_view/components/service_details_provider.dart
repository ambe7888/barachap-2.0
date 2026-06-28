import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/models/provider_model.dart';
import 'package:prohand/utils/components/custom_network_image.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class ServiceDetailsProvider extends StatelessWidget {
  final ProviderModel provider;

  const ServiceDetailsProvider({super.key, required this.provider});

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        CustomNetworkImage(
          height: 48,
          width: 48,
          radius: 24,
          imageUrl: provider.image,
          fit: BoxFit.cover,
          name: provider.name,
          userPreloader: true,
        ),
        8.toWidth,
        Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      provider.name,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: context.titleMedium?.bold,
                    ),
                    4.toWidth,
                    SvgAssets.verified.toSVGSized(20,
                        color: context.color.primarySuccessColor),
                    6.toHeight,
                    SquircleContainer(
                        padding: const EdgeInsets.symmetric(
                            vertical: 4, horizontal: 6),
                        color: context.color.mutedPendingColor,
                        radius: 8,
                        child: FittedBox(
                          child: Row(
                            children: [
                              Icon(
                                Icons.star_rounded,
                                size: 24,
                                color: context.color.primaryPendingColor,
                              ),
                              4.toWidth,
                              Text(
                                "${provider.avgRating.toStringAsFixed(1)} (${provider.ratingCount})",
                                style: context.bodySmall
                                    ?.copyWith(
                                      color: context.color.primaryPendingColor,
                                    )
                                    .bold5,
                              ),
                            ],
                          ),
                        )),
                  ],
                ),
                4.toHeight,
                RichText(
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  text: TextSpan(
                      text: provider.profession ?? "--",
                      style: context.bodyMedium?.copyWith(
                          color: context.color.secondaryContrastColor),
                      children: [
                        TextSpan(
                            text: (provider.completionRate ?? 0) <= 0
                                ? " . ${provider.completionRate}% ${LocalKeys.completionRate}"
                                : " . ${provider.completionRate}% ${LocalKeys.completionRate}")
                      ]),
                ),
              ],
            )),
        8.toWidth,
        Container(
          padding: 10.paddingAll,
          decoration: BoxDecoration(
              shape: BoxShape.circle, border: Border.all(color: primaryColor)),
          child: SvgAssets.messageDots.toSVGSized(24, color: primaryColor),
        ),
      ],
    );
  }
}
