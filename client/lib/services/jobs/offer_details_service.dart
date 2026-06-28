import 'package:flutter/foundation.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../models/job/offer_details_model.dart';

class OfferDetailsService with ChangeNotifier {
  OfferDetailsModel? _offerDetailsService;
  OfferDetailsModel get offerDetailsService =>
      _offerDetailsService ?? OfferDetailsModel();

  String token = "";

  bool shouldAutoFetch(id) =>
      _offerDetailsService?.message?.id.toString() != id || token.isInvalid;

  fetchOfferDetails({required offerId, refresh = false}) async {
    if (!refresh) {
      _offerDetailsService = null;
    }
    token = getToken;
    debugPrint(token.toString());
    final url = "${AppUrls.jobOfferDetailsUrl}/${offerId.toString()}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.offerDetails, headers: acceptJsonAuthHeader);

    try {
      if (responseData != null) {
        _offerDetailsService = OfferDetailsModel.fromJson(responseData);
      } else {}
    } catch (e) {
      debugPrint(e.toString());
    }
    notifyListeners();
  }

  void setAlreadyApplied() {
    _offerDetailsService?.message?.isHired = "1";
    notifyListeners();
  }

  tryRejectOffer() async {
    token = getToken;
    final url =
        "${AppUrls.offerRejectUrl}/${offerDetailsService.message?.id.toString()}";
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.reject, headers: acceptJsonAuthHeader);

    try {
      if (responseData != null) {
        LocalKeys.offerRejectedSuccessfully.showToast();
        try {
          _offerDetailsService?.message?.isRejected = "1";
        } catch (e) {}
        return true;
      } else {}
    } catch (e) {
      debugPrint(e.toString());
    }
    notifyListeners();
  }
}
