import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/view_models/application_details_view_model/application_details_view_model.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';

import '../../data/network/network_api_services.dart';

class TaxInfoService {
  fetchTaxInfo({required locationId, bool isJob = false}) async {
    var url = "${AppUrls.taxInfoUrl}/$locationId?type=${isJob ? "job" : ""}";

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.taxInfo, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      ServiceBookingViewModel.instance.setTax(
        responseData["tax_info"].toString().tryToParse,
        responseData["tax_type"],
      );
      OfferDetailsViewModel.instance.setTax(
        responseData["tax_info"].toString().tryToParse,
        responseData["tax_type"],
      );
      return true;
    }
  }
}
