import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';

class SubmitReviewService {
  trySubmittingReview(
      {orderId,
      suborderId,
      rating,
      message,
      serviceId,
      providerId,
      adminId}) async {
    var url = AppUrls.submitReviewsUrl;
    var data = {
      'message': '$message',
      'rating': '$rating',
      'user_id': '${providerId ?? adminId}',
      'order_id': '$orderId',
      'sub_order_id': '$suborderId',
      'service_id': '$serviceId',
      "user_type": providerId == null ? "2" : "0"
    };

    final responseData = await NetworkApiServices().postApi(
      data,
      url,
      LocalKeys.submitReview,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      LocalKeys.reviewSubmittedSuccessfully.showToast();
      return true;
    }
  }
}
