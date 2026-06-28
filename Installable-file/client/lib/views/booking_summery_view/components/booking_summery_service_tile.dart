import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../utils/components/custom_network_image.dart';
import '../../../utils/service_card/components/service_card_sub_info.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';

class BookingSummeryServiceTile extends StatelessWidget {
  const BookingSummeryServiceTile({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    final service = svm.selectedService.value;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CustomNetworkImage(
            height: 72,
            width: 72,
            radius: 10,
            fit: BoxFit.cover,
            imageUrl: service?.image,
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                ServiceCardSubInfo(
                  avgRating: service?.averageRating ?? 0,
                  unit: service?.unit,
                  category: service?.category?.name,
                  soldCount: service?.soldCount ?? 0,
                ),
                4.toHeight,
                Text(
                  service?.title ?? "---",
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleMedium?.bold,
                ),
              ],
            ),
          )
        ],
      ),
    );
  }
}
