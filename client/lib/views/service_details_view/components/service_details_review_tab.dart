import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/empty_element.dart';
import 'package:prohandy_client/views/ratings_and_review_view/components/rating_tile.dart';

import '../../../models/service/service_details_model.dart';

class ServiceDetailsReviewTab extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceDetailsReviewTab({
    super.key,
    required this.serviceDetails,
  });

  @override
  Widget build(BuildContext context) {
    return (serviceDetails.allServices?.reviews ?? []).isEmpty
        ? EmptyElement(text: LocalKeys.noRatingsFound)
        : Column(
            children: (serviceDetails.allServices?.reviews ?? []).map((review) {
              return Column(
                children: [
                  RatingTile(
                    userImage: review.reviewer?.image ?? "",
                    userName: review.reviewer?.name,
                    rating: review.rating.toDouble(),
                    description: review.message,
                    createdAt: review.createdAt,
                    padding: const EdgeInsets.symmetric(vertical: 8),
                  ),
                  if (review !=
                      (serviceDetails.allServices?.reviews ?? []).lastOrNull)
                    const SizedBox().divider,
                ],
              );
            }).toList(),
          );
  }
}
