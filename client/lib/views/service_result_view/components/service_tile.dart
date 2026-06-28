import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_price.dart';
import 'package:prohandy_client/utils/service_card/components/service_card_sub_info.dart';
import 'package:prohandy_client/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:provider/provider.dart';

import '../../../models/home_models/services_list_model.dart';
import '../../../services/home_services/service_details_service.dart';
import '../../service_details_view/service_details_view.dart';

class ServiceTile extends StatelessWidget {
  final ServiceModel service;
  const ServiceTile({super.key, required this.service});

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
      child: Container(
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: context.color.primaryBorderColor.withOpacity(0.6),
            width: 1,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomNetworkImage(
              height: 72,
              width: 72,
              radius: 8,
              fit: BoxFit.cover,
              imageUrl: service.image,
            ),
            12.toWidth,
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    service.title ?? "---",
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: context.titleSmall?.bold,
                  ),
                  ServiceCardSubInfo(
                    avgRating: service.avgRating,
                    unit: service.unit ?? "",
                    category: service.category?.name,
                    soldCount: service.soldCount,
                  ),
                  4.toHeight,
                  ServiceCardPrice(
                    price: service.price,
                    discountPrice: service.discountPrice,
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
