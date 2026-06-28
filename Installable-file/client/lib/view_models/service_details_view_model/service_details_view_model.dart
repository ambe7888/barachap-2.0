import 'package:card_swiper/card_swiper.dart';
import 'package:flutter/material.dart';

import '../../helper/local_keys.g.dart';
import '../order_list_view_model/order_status_enums.dart';

class ServiceDetailsViewModel {
  ScrollController scrollController = ScrollController();
  final SwiperController swipeController = SwiperController();

  ValueNotifier<ServiceDetailsTabsTypes> selectedTab =
      ValueNotifier(ServiceDetailsTabsTypes.overview);
  ValueNotifier selectedFAQ = ValueNotifier(null);

  final ValueNotifier<bool> showVideo = ValueNotifier(true);

  ServiceDetailsViewModel._init();
  static ServiceDetailsViewModel? _instance;
  static ServiceDetailsViewModel get instance {
    _instance ??= ServiceDetailsViewModel._init();
    return _instance!;
  }

  ServiceDetailsViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}

enum ServiceDetailsTabsTypes { overview, faq, reviews, addons, staffs }

EnumValues get serviceDetailsTabs => EnumValues({
      LocalKeys.overview: ServiceDetailsTabsTypes.overview,
      LocalKeys.faq: ServiceDetailsTabsTypes.faq,
      LocalKeys.addons: ServiceDetailsTabsTypes.addons,
      LocalKeys.reviews: ServiceDetailsTabsTypes.reviews,
      LocalKeys.staffs: ServiceDetailsTabsTypes.staffs,
    });
