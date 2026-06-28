import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/offer_list_service.dart';
import 'package:provider/provider.dart';

import '../order_list_view_model/order_status_enums.dart';

class OfferListViewModel {
  ValueNotifier<OfferType> selectedType = ValueNotifier(OfferType.all);

  ScrollController scrollController = ScrollController();

  OfferListViewModel._init();
  static OfferListViewModel? _instance;
  static OfferListViewModel get instance {
    _instance ??= OfferListViewModel._init();
    return _instance!;
  }

  OfferListViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  tryToLoadMore(BuildContext context) {
    try {
      final ol = Provider.of<OfferListService>(context, listen: false);
      final nextPage = ol.nextPage;
      final nextPageLoading = ol.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          ol.fetchNextPage();
          return;
        }
      }
    } catch (e) {}
  }
}

enum OfferType {
  all,
  pending,
  hired,
  rejected,
  shortlisted,
}

final applicationTypeValues = EnumValues({
  LocalKeys.allOffers: OfferType.all,
  LocalKeys.pending: OfferType.pending,
  LocalKeys.hired: OfferType.hired,
  LocalKeys.rejected: OfferType.rejected,
  LocalKeys.shortlisted: OfferType.shortlisted,
});
