import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/models/job/offer_list_model.dart';
import 'package:prohandy_client/views/job_offer_details_view/job_offer_details_view.dart';
import 'package:prohandy_client/views/offer_list_view/components/offer_list_tile_top.dart';

import 'offer_list_tile_provider.dart';

class OfferListTile extends StatelessWidget {
  final JobOffer offer;
  const OfferListTile({super.key, required this.offer});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(JobOfferDetailsView(offerId: offer.id));
      },
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            OfferListTileTop(
              budget: offer.budget,
              createdAt: offer.createdAt ?? DateTime.now(),
              status: offer.status,
            ),
            Divider(
              color: context.color.primaryBorderColor,
              height: 24,
              thickness: 2,
            ),
            if (offer.provider != null)
              OfferListTileProvider(
                  provider: offer.provider!, ignoreGesture: true),
          ],
        ),
      ),
    );
  }
}
