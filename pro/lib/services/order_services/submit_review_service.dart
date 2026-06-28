import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';

class SubmitReviewService {
  trySubmittingReview({
    orderId,
    suborderId,
    rating,
    message,
    serviceId,
    clientId,
    jobId,
  }) async {
    var url = AppUrls.submitReviewsUrl;
    var data = {
      'message': '$message',
      'rating': '$rating',
      'user_id': '$clientId',
      'order_id': '$orderId',
      'sub_order_id': '$suborderId',
      'service_id': '$serviceId',
      'user_type': "1",
    };
    if (jobId != null) {
      data.putIfAbsent("job_id", jobId);
    }
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
