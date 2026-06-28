import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/views/service_provider_view/service_provider_view.dart';

import '../../../models/service/service_details_model.dart';
import '../../../utils/service_card/components/service_card_price.dart';
import '../../../utils/service_card/components/service_card_sub_info.dart';
import 'service_details_provider.dart';

class ServiceDetailsBasics extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  final dynamic id;
  const ServiceDetailsBasics(
      {super.key, required this.serviceDetails, required this.id});

  @override
  Widget build(BuildContext context) {
    final service = serviceDetails.allServices!;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ServiceCardSubInfo(
            avgRating: service.averageRating,
            unit: service.unit ?? "",
            category: service.category?.name,
            soldCount: service.soldCount,
          ),
          Text(
            service.title ?? "---",
            style: context.titleLarge?.bold,
          ),
          12.toHeight,
          ServiceCardPrice(
              price: service.price, discountPrice: service.discountPrice ?? 0),
          16.toHeight,
          const SizedBox().divider,
          16.toHeight,
          GestureDetector(
            onTap: () {
              final providerId = service.provider?.id;
              if (providerId == null) return;
              context.toPage(ServiceProviderView(
                providerID: providerId,
              ));
            },
            child: ServiceDetailsProvider(sd: serviceDetails),
          )
        ],
      ),
    );
  }
}
