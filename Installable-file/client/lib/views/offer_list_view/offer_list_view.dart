import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/offer_list_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/offer_list_view_model/offer_list_view_model.dart';
import 'package:prohandy_client/views/offer_list_view/components/offer_list_tile.dart';
import 'package:provider/provider.dart';

import 'components/offer_list_skeleton.dart';

class OfferListView extends StatelessWidget {
  static const routeName = "applicants_view";
  const OfferListView({super.key});

  @override
  Widget build(BuildContext context) {
    final olm = OfferListViewModel.instance;
    olm.scrollController.addListener(() {
      olm.tryToLoadMore(context);
    });
    final olProvider = Provider.of<OfferListService>(context, listen: false);
    final jobId = context.arguments;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.offers),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await olProvider.fetchOfferList(jobId);
        },
        child: CustomFutureWidget(
          function: olProvider.shouldAutoFetch
              ? olProvider.fetchOfferList(jobId)
              : null,
          shimmer: const OfferListSkeleton(),
          child: Consumer<OfferListService>(builder: (context, jl, child) {
            if (jl.offerListModel.jobOffers.isEmpty) {
              return EmptyWidget(
                  title: LocalKeys.noOffersFound,
                  margin: const EdgeInsets.symmetric(vertical: 8));
            }
            return Scrollbar(
                controller: olm.scrollController,
                child: CustomScrollView(
                  controller: olm.scrollController,
                  physics: const AlwaysScrollableScrollPhysics(),
                  slivers: [
                    8.toHeight.toSliver,
                    SliverList.separated(
                      itemBuilder: (context, index) {
                        final offer = jl.offerListModel.jobOffers[index];
                        return OfferListTile(
                          offer: offer,
                        );
                      },
                      separatorBuilder: (context, index) => 8.toHeight,
                      itemCount: jl.offerListModel.jobOffers.length,
                    )
                  ],
                ));
          }),
        ),
      ),
    );
  }
}
