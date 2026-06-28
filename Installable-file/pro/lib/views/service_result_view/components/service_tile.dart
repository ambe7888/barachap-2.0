import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/models/service_models/service_model.dart';
import 'package:prohand/utils/components/custom_network_image.dart';
import 'package:prohand/utils/service_card/components/service_card_sub_info.dart';

class ServiceTile extends StatelessWidget {
  final Service service;
  const ServiceTile({super.key, required this.service});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
      color: Colors.transparent,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CustomNetworkImage(
            height: 64,
            width: 64,
            radius: 10,
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
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleSmall?.bold,
                ),
                4.toHeight,
                ServiceCardSubInfo(
                  avgRating: service.avgRating,
                  unit: service.unit ?? "",
                  category: service.category,
                )
              ],
            ),
          )
        ],
      ),
    );
  }
}
