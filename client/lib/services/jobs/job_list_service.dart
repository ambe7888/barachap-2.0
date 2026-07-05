import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/job/job_list_model.dart';
import 'package:prohandy_client/view_models/job_list_view_model/job_list_view_model.dart';

import '../../data/network/network_api_services.dart';

class JobListService with ChangeNotifier {
  JobListModel? _jobListModel;
  JobListModel get jobListModel => _jobListModel ?? JobListModel();

  String token = "";
  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;
  bool isLoading = false;

  bool get shouldAutoFetch => _jobListModel == null || token.isInvalid;

  fetchJobList({bool refresh = false}) async {
    final jlm = JobListViewModel.instance;
    String filter = "";

    token = getToken;
    var url = AppUrls.jobListUrl + filter;
    debugPrint(url.toString());

    final responseData = await NetworkApiServices().getApi(url, LocalKeys.jobs,
        headers: acceptJsonAuthHeader, timeoutSeconds: 30);

    try {
      if (responseData != null) {
        final tempData = JobListModel.fromJson(responseData);
        _jobListModel = tempData;
        nextPage = tempData.pagination?.nextPageUrl;
        isLoading = false;
        notifyListeners();
        return true;
      } else {
        _jobListModel ??= JobListModel();
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
      tempData.jobs?.forEach((element) {
        _jobListModel?.jobs?.add(element);
      });
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

  Future<bool> tryChangingJobPublishStatus(Job job) async {
    var url = "${AppUrls.changeJobPublishStatusUrl}/${job.id}";
    var data = {};

    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.publish, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        final previousStatus = (job.isPublished).toString().parseToBool;
        job.isPublished = previousStatus ? "0" : "1";
        LocalKeys.jobPublishStatusChangedSuccessfully.showToast();
      } catch (e) {
        debugPrint(e.toString());
      }
      notifyListeners();
      return true;
    }
    return false;
  }
}
