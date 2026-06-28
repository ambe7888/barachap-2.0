import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';
import 'package:provider/provider.dart';

import '../../../services/booking_services/tax_info_service.dart';
import '../../../services/jobs/offer_details_service.dart';
import '../../../utils/components/custom_future_widget.dart';
import '../../../utils/components/custom_preloader.dart';
import '../../../view_models/application_details_view_model/application_details_view_model.dart';

class JobHireSummaryCostInfo extends StatelessWidget {
  const JobHireSummaryCostInfo({super.key});

  @override
  Widget build(BuildContext context) {
    final adm = OfferDetailsViewModel.instance;
    final odProvider = Provider.of<OfferDetailsService>(context, listen: false);
    final jobDetails = Provider.of<JobDetailsService>(context, listen: false)
        .jobDetailsModel
        .jobDetails;
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: ValueListenableBuilder(
        valueListenable: adm.taxNotifier,
        builder: (context, value, child) => CustomFutureWidget(
          function: adm.taxCalculated
              ? null
              : TaxInfoService().fetchTaxInfo(
                  locationId: jobDetails?.jobLocation?.id, isJob: true),
          shimmer: const CustomPreloader(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(
                  title: LocalKeys.offered,
                  value: (odProvider.offerDetailsService.message?.budget ?? 0)
                      .cur),
              12.toHeight,
              InfoTile(
                  title: LocalKeys.vat,
                  value: adm
                      .getCalculatedTax(
                          (odProvider.offerDetailsService.message?.budget ?? 0))
                      .cur),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              InfoTile(
                  title: LocalKeys.total,
                  value:
                      ((odProvider.offerDetailsService.message?.budget ?? 0) +
                              adm.getCalculatedTax((odProvider!
                                      .offerDetailsService.message?.budget ??
                                  0)))
                          .cur),
            ],
          ),
        ),
      ),
    );
  }
}
