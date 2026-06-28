import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/home_models/services_list_model.dart';
import 'package:prohandy_client/services/home_services/service_details_service.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_image.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_price.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_provider.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_sub_info.dart';
import 'package:prohandy_client/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:prohandy_client/views/service_details_view/service_details_view.dart';
import 'package:provider/provider.dart';

import '../components/custom_squircle_widget.dart';

class ServiceCard extends StatelessWidget {
  final ServiceModel service;
  const ServiceCard({
    super.key,
    required this.service,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        ServiceDetailsViewModel.instance.selectedTab.value =
            ServiceDetailsTabsTypes.overview;
        context.toPage(ServiceDetailsView(id: service.id), then: (_) {
          Provider.of<ServiceDetailsService>(context, listen: false)
              .remove(service.id);
          ServiceDetailsViewModel.instance.selectedTab.value =
              ServiceDetailsTabsTypes.overview;
        });
      },
      child: SquircleContainer(
        width: 188,
        height: 312,
        padding: const EdgeInsets.all(8),
        radius: 16,
        borderColor: context.color.primaryBorderColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ServiceCardImage(
              imageUrl: service.image,
              service: service,
            ),
            12.toHeight,
            ServiceCardSubInfo(
              avgRating: service.avgRating,
              unit: service.unit ?? "",
              category: service.category?.name,
              soldCount: 0,
            ),
            6.toHeight,
            Text(
              service.title ?? "---",
              style: context.titleSmall?.bold6,
              overflow: TextOverflow.ellipsis,
              maxLines: 2,
            ),
            6.toHeight,
            ServiceCardPrice(
                price: service.price, discountPrice: service.discountPrice),
            10.toHeight,
            const Expanded(
              child: SizedBox(),
            ),
            Divider(
              height: 1,
              color: context.color.mutedContrastColor,
              thickness: 1,
            ),
            10.toHeight,
            ServiceCardProvider(
              imageUrl: (service.provider?.image ?? service.admin?.image) ?? "",
              name: (service.provider?.name ?? service.admin?.name) ?? "",
              id: (service.provider?.id ?? service.admin?.id)?.toString() ?? "",
            )
          ],
        ),
      ),
    );
  }
}
