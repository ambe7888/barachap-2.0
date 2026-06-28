import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/job_hire_summary_view/components/hire_summary_address.dart';
import 'package:prohandy_client/views/job_hire_summary_view/components/job_summery_date_schedule.dart';
import 'package:prohandy_client/views/job_offer_details_view/components/offer_details_provider_tile.dart';
import 'package:provider/provider.dart';

import '../../services/jobs/offer_details_service.dart';
import 'components/job_hire_summary_cost_info.dart';
import 'components/job_summary_phone.dart';
import 'components/job_summery_button.dart';

class JobHireSummaryView extends StatelessWidget {
  static const routeName = "job_hire_summary_view";
  const JobHireSummaryView({super.key});

  @override
  Widget build(BuildContext context) {
    final jdProvider = Provider.of<JobDetailsService>(context, listen: false);
    final odProvider = Provider.of<OfferDetailsService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.summary),
      ),
      body: jdProvider.jobDetailsModel.jobDetails == null
          ? EmptyWidget(title: LocalKeys.jobDetails)
          : SingleChildScrollView(
              padding: 16.paddingV,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (odProvider.offerDetailsService.message?.provider !=
                      null) ...[
                    OfferDetailsProviderTile(
                        provider:
                            odProvider.offerDetailsService.message!.provider!),
                    8.toHeight
                  ],
                  SummeryDateSchedule(
                    date: jdProvider.jobDetailsModel.jobDetails!.date,
                    schedule:
                        jdProvider.jobDetailsModel.jobDetails!.time ?? "---",
                  ),
                  if (jdProvider
                          .jobDetailsModel.jobDetails?.jobLocation?.address !=
                      null) ...[
                    8.toHeight,
                    SummaryAddress(
                        address: jdProvider!
                            .jobDetailsModel.jobDetails!.jobLocation),
                  ],
                  if (jdProvider
                          .jobDetailsModel.jobDetails?.jobLocation?.phone !=
                      null) ...[
                    8.toHeight,
                    SummaryPhone(
                        address: jdProvider!
                            .jobDetailsModel.jobDetails!.jobLocation!)
                  ],
                  8.toHeight,
                  const JobHireSummaryCostInfo(),
                ],
              ),
            ),
      bottomNavigationBar: const JobSummeryButton(),
    );
  }
}
