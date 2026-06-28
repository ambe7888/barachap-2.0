import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/job_services/job_list_service.dart';
import 'package:prohand/view_models/order_list_view_model/order_status_enums.dart';
import 'package:provider/provider.dart';

class JobListViewModel {
  ScrollController scrollController = ScrollController();

  final TextEditingController titleController = TextEditingController();

  final ValueNotifier<JobListFilterTypes?> selectedType =
      ValueNotifier(JobListFilterTypes.all);
  final ValueNotifier<JobListViewTypes> viewType =
      ValueNotifier(JobListViewTypes.list);

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

  void changeViewType() {
    if (viewType.value == JobListViewTypes.list) {
      viewType.value = JobListViewTypes.map;
      return;
    }
    viewType.value = JobListViewTypes.list;
  }

  tryToLoadMore(BuildContext context) async {
    try {
      final jl = Provider.of<JobListService>(context, listen: false);
      final nextPage = jl.nextPage;
      final nextPageLoading = jl.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          await jl.fetchNextPage();
          return true;
        }
      }
    } catch (e) {}
  }
}

enum JobListFilterTypes {
  all,
  applied,
  saved,
}

enum JobListViewTypes {
  list,
  map,
}

var jobListFilterValues = EnumValues({
  LocalKeys.allJobs: JobListFilterTypes.all,
  LocalKeys.jobsIApplied: JobListFilterTypes.applied,
  LocalKeys.savedJobs: JobListFilterTypes.saved,
});
