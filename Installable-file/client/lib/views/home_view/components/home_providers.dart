import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/views/home_view/components/home_provider_card.dart';
import 'package:provider/provider.dart';

import '../../../services/home_services/home_providers_service.dart';
import 'home_providers_skeleton.dart';

class HomeProviders extends StatelessWidget {
  const HomeProviders({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<HomeProvidersService>(builder: (context, hp, child) {
      return CustomFutureWidget(
        function: hp.providerList == null ? hp.fetchHomeProviders() : null,
        shimmer: const HomeProvidersSkeleton(),
        child: (hp.providerList ?? []).isEmpty
            ? const SizedBox()
            : Container(
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                ),
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(
                    vertical: 16,
                    horizontal: 20,
                  ),
                  child: Wrap(
                    spacing: 8,
                    children: hp.providerList!
                        .map((e) => HomeProvidersCard(provider: e))
                        .toList(),
                  ),
                ),
              ),
      );
    });
  }
}
