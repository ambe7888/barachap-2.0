import 'package:flutter/material.dart';
import 'package:prohandy_client/services/jobs/job_list_service.dart';
import 'package:provider/provider.dart';

class JobListViewModel {
  ScrollController scrollController = ScrollController();
  final GlobalKey<RefreshIndicatorState> refreshKey =
      GlobalKey<RefreshIndicatorState>();
  JobListViewModel._init();
  static JobListViewModel? _instance;
  static JobListViewModel get instance {
    _instance ??= JobListViewModel._init();
    return _instance!;
  }

  JobListViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  tryToLoadMore(BuildContext context) {
    try {
      final jl = Provider.of<JobListService>(context, listen: false);
      final nextPage = jl.nextPage;
      final nextPageLoading = jl.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          jl.fetchNextPage();
          return;
        }
      }
    } catch (e) {}
  }
}
