import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/ratings_and_review_view/components/rating_tile.dart';
import 'package:provider/provider.dart';

import '../../services/profile_services/review_list_service.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../../utils/components/empty_widget.dart';
import '../../utils/components/scrolling_preloader.dart';
import '../../view_models/ratings_and_review_view_model/ratings_and_review_view_model.dart';
import 'components/rr_list_skeleton.dart';

class RatingsAndReviewView extends StatelessWidget {
  const RatingsAndReviewView({super.key});

  @override
  Widget build(BuildContext context) {
    final rrProvider = Provider.of<ReviewListService>(context, listen: false);
    final rrm = RatingsAndReviewViewModel.instance;
    rrm.scrollController.addListener(() {
      rrm.tryToLoadMore(context);
    });
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          LocalKeys.ratingAndReviews,
        ),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await rrProvider.fetchReviewList();
        },
        child: CustomFutureWidget(
          function:
              rrProvider.shouldAutoFetch ? rrProvider.fetchReviewList() : null,
          shimmer: const RrListSkeleton(),
          child: Consumer<ReviewListService>(builder: (context, rr, child) {
            if (rr.reviewListModel.reviews.isEmpty) {
              return EmptyWidget(
                  title: LocalKeys.noRatingsFound,
                  margin: const EdgeInsets.symmetric(vertical: 8));
            }
            return Scrollbar(
                controller: rrm.scrollController,
                child: CustomScrollView(
                  controller: rrm.scrollController,
                  physics: const AlwaysScrollableScrollPhysics(),
                  slivers: [
                    8.toHeight.toSliver,
                    SliverList.separated(
                      itemBuilder: (context, index) {
                        final review = rr.reviewListModel.reviews[index];
                        debugPrint(review.toString());
                        return RatingTile(
                          title: review.service ?? LocalKeys.na,
                          rating: review.rating.toDouble(),
                          createdAt: review.createdAt,
                          description: review.message,
                        );
                      },
                      separatorBuilder: (context, index) =>
                          const SizedBox().divider,
                      itemCount: rr.reviewListModel.reviews.length,
                    ),
                    16.toHeight.toSliver,
                    if (rr.nextPage != null && !rr.nexLoadingFailed)
                      ScrollPreloader(loading: rr.nextPageLoading).toSliver,
                    16.toHeight.toSliver,
                  ],
                ));
          }),
        ),
      ),
    );
  }
}
