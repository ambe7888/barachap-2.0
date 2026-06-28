import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/job/job_details_model.dart';

class JobDetailsService with ChangeNotifier {
  JobDetailsModel? _jobDetailsModel;
  JobDetailsModel get jobDetailsModel => _jobDetailsModel ?? JobDetailsModel();

  String token = "";

  bool shouldAutoFetch(id) =>
      _jobDetailsModel?.jobDetails?.id?.toString() != id.toString() ||
      token.isInvalid;

  fetchOrderDetails({required jobId, refresh = false}) async {
    if (!refresh) {
      _jobDetailsModel = null;
    }
    token = getToken;
    final url = "${AppUrls.jobDetailsUrl}/${jobId.toString()}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.jobDetails, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _jobDetailsModel = JobDetailsModel.fromJson(responseData);
    } else {}
    _jobDetailsModel ??= JobDetailsModel();
    notifyListeners();
  }

  void setAlreadyApplied() {
    notifyListeners();
  }

  tryChangingJobPublishStatus() async {
    var url =
        "${AppUrls.changeJobPublishStatusUrl}/${jobDetailsModel.jobDetails?.id}";
    var data = {};

    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.publish, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        final previousStatus =
            (jobDetailsModel.jobDetails?.isPublished).toString().parseToBool;
        _jobDetailsModel?.jobDetails?.isPublished = previousStatus ? "0" : "1";
        LocalKeys.jobPublishStatusChangedSuccessfully.showToast();
      } catch (e) {
        debugPrint(e.toString());
      }
      notifyListeners();
      return true;
    }
  }

  tryDeletingJob() async {
    var url = "${AppUrls.deleteJobUrl}/${jobDetailsModel.jobDetails?.id}";
    var data = {};

    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.delete, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      try {
        LocalKeys.jobDeletedSuccessfully.showToast();
      } catch (e) {
        debugPrint(e.toString());
      }
      return true;
    }
  }
}
