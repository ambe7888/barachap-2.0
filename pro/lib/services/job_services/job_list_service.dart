import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/job_models/job_list_model.dart';
import 'package:prohand/view_models/job_list_view_model/job_list_view_model.dart';

import '../../data/network/network_api_services.dart';

class JobListService with ChangeNotifier {
  JobListModel? _jobListModel;
  JobListModel get jobListModel => _jobListModel ?? JobListModel(jobs: []);

  String token = "";
  String title = "";
  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  bool get shouldAutoFetch => _jobListModel == null || token.isInvalid;

  fetchJobList({bool refresh = false}) async {
    final msm = JobListViewModel.instance;
    title = msm.titleController.text;
    String filter =
        "?title=$title&applied_jobs=${msm.selectedType.value == JobListFilterTypes.applied ? 1 : ""}";

    token = getToken;
    if (!refresh) {
      isLoading = true;
      notifyListeners();
    }
    var url = AppUrls.jobListUrl + filter;
    debugPrint(url.toString());

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.jobs, headers: acceptJsonAuthHeader);

    try {
      if (responseData != null) {
        final tempData = JobListModel.fromJson(responseData);
        _jobListModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
        isLoading = false;
        notifyListeners();
        return true;
      } else {
        _jobListModel ??= JobListModel(jobs: []);
      }
    } catch (e) {
      debugPrint(e.toString());
      log(e.toString());
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .getApi(nextPage, LocalKeys.jobs, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = JobListModel.fromJson(responseData);
      for (var element in tempData.jobs) {
        _jobListModel?.jobs.add(element);
      }
      nextPage = tempData.pagination?.nextPageUrl;
    } else {
      nexLoadingFailed = true;
      Future.delayed(const Duration(seconds: 1)).then((value) {
        nexLoadingFailed = false;
        notifyListeners();
      });
    }
    nextPageLoading = false;
    notifyListeners();
  }
}
