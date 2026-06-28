import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/service_card/service_card.dart';
import 'package:provider/provider.dart';

import '../../../services/home_services/home_popular_services_service.dart';
import 'services_horizontal_skeleton.dart';

class HomePopularServices extends StatelessWidget {
  const HomePopularServices({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<HomePopularServicesService>(builder: (context, hc, child) {
      return CustomFutureWidget(
        function: hc.shouldAutoFetch ? hc.fetchHomePopularServices() : null,
        shimmer: const ServicesHorizontalSkeleton(),
        child: hc.homePopularServicesModel.allServices.isEmpty
            ? const SizedBox()
            : Container(
                color: context.color.accentContrastColor,
                margin: const EdgeInsets.only(top: 8),
                padding: const EdgeInsets.symmetric(vertical: 16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 24),
                      child: Text(
                        LocalKeys.popularServices,
                        style: context.titleMedium?.bold,
                      ),
                    ),
                    8.toHeight,
                    SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      padding: const EdgeInsets.symmetric(horizontal: 24),
                      child: Wrap(
                        spacing: 16,
                        children: hc.homePopularServicesModel.allServices
                            .map((e) => ServiceCard(
                                  service: e,
                                ))
                            .toList(),
                      ),
                    ),
                  ],
                ),
              ),
      );
    });
  }
}
