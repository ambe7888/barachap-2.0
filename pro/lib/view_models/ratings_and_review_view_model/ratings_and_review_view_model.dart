import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../services/profile_services/review_list_service.dart';

class RatingsAndReviewViewModel {
  final ScrollController scrollController = ScrollController();

  RatingsAndReviewViewModel._init();
  static RatingsAndReviewViewModel? _instance;
  static RatingsAndReviewViewModel get instance {
    _instance ??= RatingsAndReviewViewModel._init();
    return _instance!;
  }

  RatingsAndReviewViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  tryToLoadMore(BuildContext context) {
    try {
      final rrProvider = Provider.of<ReviewListService>(context, listen: false);
      final nextPage = rrProvider.nextPage;
      final nextPageLoading = rrProvider.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          rrProvider.fetchNextPage();
          return;
        }
      }
    } catch (e) {}
  }
}
