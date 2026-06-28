import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/provider_model.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/view_models/service_provider_view_model/service_provider_view_model.dart';
import 'package:prohandy_client/views/service_provider_view/service_provider_view.dart';

import 'home_provider_card_image_rating.dart';

class HomeProvidersCard extends StatelessWidget {
  final ProviderModel provider;
  const HomeProvidersCard({super.key, required this.provider});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        ServiceProviderViewModel.dispose;
        context.toPage(ServiceProviderView(
          providerID: provider.id,
        ));
      },
      child: SquircleContainer(
        width: context.width * 0.373,
        padding: const EdgeInsets.symmetric(
          horizontal: 10,
          vertical: 12,
        ),
        radius: 10,
        borderColor: context.color.primaryBorderColor,
        child: Column(
          children: [
            HomeProviderCardImageRating(provider: provider),
            8.toHeight,
            Text(
              provider.name,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: context.titleSmall?.bold,
            ),
            if (provider.serviceCategories?.firstOrNull?.name != null) ...[
              4.toHeight,
              Text(
                provider.serviceCategories!.firstOrNull!.name!,
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: context.bodySmall
                    ?.copyWith(color: context.color.secondaryContrastColor),
              )
            ],
          ],
        ),
      ),
    );
  }
}
