import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/booking_services/tax_info_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';

import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';
import 'booking_summery_addons.dart';

class BookingSummeryCostInfo extends StatelessWidget {
  const BookingSummeryCostInfo({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    final service = ((svm.selectedService.value?.discountPrice ?? 0) > 0
        ? svm.selectedService.value!.discountPrice!
        : (svm.selectedService.value?.price ?? 0));
    num addonTotal = 0;
    final addons = svm.addons.value.values
        .map((addon) => addon["price"] * addon["quantity"])
        .toList();
    for (var t in addons) {
      addonTotal += t;
    }
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: ValueListenableBuilder(
        valueListenable: svm.taxNotifier,
        builder: (context, value, child) => CustomFutureWidget(
          function: svm.taxCalculated
              ? null
              : TaxInfoService()
                  .fetchTaxInfo(locationId: svm.selectedAddress.value?.id),
          shimmer: const CustomPreloader(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(title: LocalKeys.package, value: service.cur),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              const BookingSummeryAddons(),
              InfoTile(
                  title: LocalKeys.subtotal, value: (addonTotal + service).cur),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              InfoTile(
                title: LocalKeys.total,
                value: (addonTotal + service).cur,
                fontSize: 18,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
