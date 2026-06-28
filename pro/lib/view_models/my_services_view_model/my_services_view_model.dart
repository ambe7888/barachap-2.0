import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/service_services/service_list_service.dart';
import 'package:provider/provider.dart';

class MyServicesViewModel {
  ScrollController scrollController = ScrollController();
  final GlobalKey<RefreshIndicatorState> refreshKey =
      GlobalKey<RefreshIndicatorState>();

  final ValueNotifier<String> selectedStatus = ValueNotifier(LocalKeys.all);

  final TextEditingController titleController = TextEditingController();

  final serviceStatusValues = {
    LocalKeys.all: "",
    LocalKeys.pending: "0",
    LocalKeys.active: "1",
    LocalKeys.suspended: "2",
  };
  MyServicesViewModel._init();
  static MyServicesViewModel? _instance;
  static MyServicesViewModel get instance {
    _instance ??= MyServicesViewModel._init();
    return _instance!;
  }

  MyServicesViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  tryToLoadMore(BuildContext context) async {
    try {
      final sl = Provider.of<ServiceListService>(context, listen: false);
      final nextPage = sl.nextPage;
      final nextPageLoading = sl.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          await sl.fetchNextPage();
          return;
        }
      }
    } catch (e) {}
  }
}

enum ServiceStatus { pending, active, suspended }
