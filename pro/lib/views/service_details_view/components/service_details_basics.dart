import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/models/service_models/service_details_model.dart';
import 'package:prohand/services/service_services/service_details_service.dart';

import '../../../utils/service_card/components/service_card_price.dart';
import '../../../utils/service_card/components/service_card_sub_info.dart';
import 'service_details_publish_status.dart';

class ServiceDetailsBasics extends StatelessWidget {
  final ServiceDetailsService sd;
  const ServiceDetailsBasics({super.key, required this.sd});

  @override
  Widget build(BuildContext context) {
    final serviceDetails = sd.serviceDetailsModel.allServices ??
        AllServices(price: 0, isPublished: false);
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ServiceDetailsPublishStatus(
            sd: sd,
          ),
          const SizedBox().divider,
          16.toHeight,
          Text(
            serviceDetails.title ?? "---",
            style: context.titleLarge?.bold,
          ),
          ServiceCardSubInfo(
            avgRating: serviceDetails.averageRating ?? 0,
            unit: serviceDetails.unit ?? "",
            category: serviceDetails.category?.name,
            soldCount: serviceDetails.soldCount,
          ),
          16.toHeight,
          ServiceCardPrice(
            price: serviceDetails.price,
            discountPrice: serviceDetails.discountPrice ?? 0,
            views: serviceDetails.view,
            ordersCount: serviceDetails.soldCount,
          ),
        ],
      ),
    );
  }
}
