import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/service_details_view/components/service_details_rating_tile.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/service_services/service_details_service.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsReviewTab extends StatelessWidget {
  const ServiceDetailsReviewTab({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      return (sd.serviceDetailsModel.allServices?.addons ?? []).isEmpty
          ? SizedBox(
              width: double.infinity,
              height: 250,
              child: EmptyElement(text: LocalKeys.noReviewFound))
          : Column(
              children: (sd.serviceDetailsModel.allServices?.reviews ?? [])
                  .map((review) {
                return Column(
                  children: [
                    ServiceDetailsRatingTile(
                      userImage: review.reviewer?.image ?? "",
                      userName: review.reviewer?.name ?? "*****",
                      rating: review.rating.toDouble(),
                      description: review.message,
                      createdAt: review.createdAt,
                    ),
                    if (review.id !=
                        (sd.serviceDetailsModel.allServices?.reviews ?? [])
                            .lastOrNull
                            ?.id)
                      const SizedBox().divider,
                  ],
                );
              }).toList(),
            );
    });
  }
}
