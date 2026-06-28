import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:provider/provider.dart';

import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../utils/components/alerts.dart';
import '../my_services_view_model/my_services_view_model.dart';
import '../order_list_view_model/order_status_enums.dart';

class ServiceDetailsViewModel {
  ScrollController scrollController = ScrollController();

  ValueNotifier<ServiceDetailsTabsTypes> selectedTab =
      ValueNotifier(ServiceDetailsTabsTypes.overview);
  ValueNotifier selectedFAQ = ValueNotifier(null);

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

  void tryDeletingService(BuildContext context) async {
    final jdProvider =
        Provider.of<ServiceDetailsService>(context, listen: false);
    Alerts().confirmationAlert(
      context: context,
      title: LocalKeys.deleteServiceConfirmation,
      onConfirm: () async {
        await jdProvider.tryDeletingService().then((r) {
          if (r == true) {
            MyServicesViewModel.instance.refreshKey.currentState?.show();
            context.pop;
          }
          context.pop;
        });
      },
      buttonText: LocalKeys.delete,
      buttonColor: color.primaryWarningColor,
      description: LocalKeys.deleteServiceDescription,
    );
  }
}

enum ServiceDetailsTabsTypes {
  overview,
  faq,
  reviews,
  addons,
  staffs,
}

final serviceDetailsTabs = EnumValues({
  LocalKeys.overview: ServiceDetailsTabsTypes.overview,
  LocalKeys.faq: ServiceDetailsTabsTypes.faq,
  LocalKeys.reviews: ServiceDetailsTabsTypes.reviews,
  LocalKeys.addons: ServiceDetailsTabsTypes.addons,
  LocalKeys.staffs: ServiceDetailsTabsTypes.staffs,
});
