import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/service_card/components/service_card_image.dart';
import 'package:prohand/utils/service_card/components/service_card_price.dart';
import 'package:prohand/utils/service_card/components/service_card_provider.dart';
import 'package:prohand/utils/service_card/components/service_card_sub_info.dart';
import 'package:prohand/views/service_details_view/service_details_view.dart';

import '../../models/service_models/service_model.dart';
import '../components/custom_squircle_widget.dart';

class ServiceCard extends StatelessWidget {
  final Service service;
  const ServiceCard({
    super.key,
    required this.service,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(const ServiceDetailsView(
          id: "15",
        ));
      },
      child: SquircleContainer(
        width: 188,
        padding: const EdgeInsets.all(8),
        radius: 10,
        borderColor: context.color.primaryBorderColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ServiceCardImage(
              isFavorite: service.isFavorite,
              imageUrl: service.image,
            ),
            12.toHeight,
            ServiceCardSubInfo(
              avgRating: service.avgRating,
              unit: service.unit ?? "",
              category: service.category,
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
              price: service.price,
              discountPrice: service.discountPrice,
              views: 12,
              ordersCount: 5,
            ),
            10.toHeight,
            Divider(
              height: 2,
              color: context.color.mutedContrastColor,
              thickness: 2,
            ),
            10.toHeight,
            ServiceCardProvider(
                imageUrl: service.providerImage.toString(),
                name: service.providerName)
          ],
        ),
      ),
    );
  }
}
