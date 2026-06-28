import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/local_keys.g.dart';
import '../../models/job_models/job_details_model.dart';
import '../../view_models/job_details_view_model/job_details_view_model.dart';

class JobDetailsService with ChangeNotifier {
  JobDetailsModel? _jobDetailsModel;
  JobDetailsModel get jobDetailsModel => _jobDetailsModel ?? JobDetailsModel();

  String token = "";

  fetchJobDetails(id, {refresh = false}) async {
    var url = "${AppUrls.jobDetailsUrl}/$id";
    debugPrint("token is invalid- ${token.isInvalid}".toString());
    if (jobDetailsModel.jobDetails?.id?.toString() != id.toString() ||
        token.isInvalid) {
      _jobDetailsModel = null;
    } else if (!refresh) {
      return;
    }
    token = getToken;
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.jobDetails, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _jobDetailsModel = JobDetailsModel.fromJson(responseData);
    }
    notifyListeners();
  }

  trySendingOffer(id) async {
    final jdm = JobDetailsViewModel.instance;
    var url = AppUrls.sendOfferUrl;
    var data = {
      'job_post_id': '$id',
      'budget': jdm.priceController.text,
      'cover_letter': jdm.coverLetterController.text,
    };

    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.sendOffer, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.offerSentSuccessfully.showToast();
      return true;
    }
  }

  shouldAutoFetch(id) {
    return jobDetailsModel.jobDetails?.id?.toString() != id.toString() ||
        token.isInvalid;
  }
}
