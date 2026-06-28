import 'package:flutter/material.dart';
import 'package:prohandy_client/services/jobs/offer_details_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/views/job_offer_details_view/components/offer_details_provider_tile.dart';
import 'package:provider/provider.dart';

import '../../helper/extension/int_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../utils/components/navigation_pop_icon.dart';
import 'components/offer_details_buttons.dart';
import 'components/offer_details_cover_letter.dart';
import 'components/offer_details_price_matching.dart';
import 'components/offer_details_provider_sub_info.dart';
import 'components/offer_details_skeleton.dart';

class JobOfferDetailsView extends StatelessWidget {
  static const routeName = "job_Offer_details_view";
  final dynamic offerId;
  const JobOfferDetailsView({super.key, required this.offerId});

  @override
  Widget build(BuildContext context) {
    final odProvider = Provider.of<OfferDetailsService>(context, listen: false);
    return Builder(builder: (context) {
      return Scaffold(
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.offerDetails),
        ),
        body: CustomRefreshIndicator(
          onRefresh: () async {
            await odProvider!
                .fetchOfferDetails(offerId: offerId.toString(), refresh: true);
          },
          child: CustomFutureWidget(
            function: odProvider.shouldAutoFetch(offerId.toString())
                ? odProvider.fetchOfferDetails(offerId: offerId.toString())
                : null,
            shimmer: const OfferDetailsSkeleton(),
            child: Consumer<OfferDetailsService>(builder: (context, od, child) {
              if (od.offerDetailsService.message == null) {
                return EmptyWidget(
                  title: LocalKeys.offerDetails,
                  margin: const EdgeInsets.symmetric(vertical: 8),
                );
              }
              return Column(
                children: [
                  Expanded(
                    child: SingleChildScrollView(
                      padding: 8.paddingV,
                      physics: const AlwaysScrollableScrollPhysics(),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          OfferDetailsPriceMatching(
                            budget: od.offerDetailsService.message!.budget,
                            createdAt:
                                od.offerDetailsService.message?.createdAt ??
                                    DateTime.now(),
                            status: od.offerDetailsService.message?.status,
                          ),
                          if (od.offerDetailsService.message!.provider !=
                              null) ...[
                            8.toHeight,
                            OfferDetailsProviderTile(
                                provider:
                                    od.offerDetailsService.message!.provider!),
                            8.toHeight,
                            OfferDetailsProviderSubInfo(
                                provider:
                                    od.offerDetailsService.message!.provider!)
                          ],
                          if ((od.offerDetailsService.message!.coverLetter ??
                                  "")
                              .isNotEmpty) ...[
                            8.toHeight,
                            OfferDetailsCoverLetter(
                                coverLetter: od
                                    .offerDetailsService.message!.coverLetter!),
                          ],
                        ],
                      ),
                    ),
                  ),
                  if (odProvider.offerDetailsService.message?.status
                          ?.toString() ==
                      "0")
                    const OfferDetailsButtons(),
                ],
              );
            }),
          ),
        ),
      );
    });
  }
}
