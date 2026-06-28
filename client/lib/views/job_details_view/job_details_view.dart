import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/job_details_view_model/job_details_view_model.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';
import 'package:prohandy_client/views/job_details_view/components/job_details_address.dart';
import 'package:prohandy_client/views/job_details_view/components/job_details_date_schedule.dart';
import 'package:prohandy_client/views/job_details_view/components/job_details_description.dart';
import 'package:prohandy_client/views/post_job_view/post_job_view.dart';
import 'package:provider/provider.dart';

import '../../models/job/job_details_model.dart';
import '../../utils/components/custom_button.dart';
import '../job_offer_details_view/components/offer_details_provider_tile.dart';
import 'components/job_details_gellary.dart';
import 'components/job_details_offer.dart';
import 'components/job_details_offers.dart';
import 'components/job_details_publish_status.dart';
import 'components/job_details_skeleton.dart';
import 'components/job_details_title_budget.dart';

class JobDetailsView extends StatelessWidget {
  static const routeName = "job_details_view";
  const JobDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    final jdProvider = Provider.of<JobDetailsService>(context, listen: false);
    final jobId = context.arguments;
    return Consumer<JobDetailsService>(builder: (context, jd, child) {
      return Scaffold(
          backgroundColor: context.color.backgroundColor,
          appBar: AppBar(
            leading: const NavigationPopIcon(),
            title: Text(LocalKeys.jobDetails),
          ),
          body: CustomRefreshIndicator(
            onRefresh: () async {
              await jdProvider.fetchOrderDetails(jobId: jobId);
            },
            child: CustomFutureWidget(
                function: jdProvider.shouldAutoFetch(jobId)
                    ? jdProvider.fetchOrderDetails(jobId: jobId)
                    : null,
                shimmer: const JobDetailsSkeleton(),
                child: Builder(builder: (context) {
                  if (jd.jobDetailsModel.jobDetails == null) {
                    return EmptyWidget(title: LocalKeys.jobDetails);
                  }
                  final jobDetails = jd.jobDetailsModel.jobDetails!;
                  return SingleChildScrollView(
                    padding: 8.paddingV,
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        JobDetailsTitleBudget(
                          title: jobDetails.title ?? "---",
                          budget: jobDetails.budget,
                          jobOffersCount: jobDetails.jobOffers.length,
                          publishStatus:
                              jobDetails.isPublished.toString().parseToBool,
                        ),
                        8.toHeight,
                        JobDetailsPublishStatus(
                          publishStatus:
                              jobDetails.isPublished.toString().parseToBool,
                        ),
                        8.toHeight,
                        JobDetailsDateSchedule(
                            date: jobDetails.date,
                            schedule: DateFormat(
                                    "hh:mm a", context.dProvider.languageSlug)
                                .format(DateFormat("HH:mm:ss")
                                    .parse(jobDetails.time ?? ""))),
                        8.toHeight,
                        JobDetailsAddress(address: jobDetails.jobLocation),
                        8.toHeight,
                        JobDetailsDescription(
                            description: jobDetails.description ?? ""),
                        if (jobDetails.galleryImages.isNotEmpty) ...[
                          8.toHeight,
                          JobDetailsGallery(gallery: jobDetails.galleryImages)
                        ],
                        if (jobDetails.jobOffers.isNotEmpty &&
                            jd.jobDetailsModel.isJobHired != "1") ...[
                          8.toHeight,
                          JobDetailsOffers(offers: jobDetails.jobOffers)
                        ],
                        if (jobDetails.jobOffers.isNotEmpty &&
                            jd.jobDetailsModel.isJobHired == "1") ...[
                          8.toHeight,
                          Builder(builder: (context) {
                            JobOffer? hiredOffer;
                            try {
                              hiredOffer = jobDetails.jobOffers
                                  .firstWhere((j) => j.isHired);
                            } catch (e) {}
                            if (hiredOffer == null) {
                              return const SizedBox();
                            }
                            return Column(
                              children: [
                                JobDetailsOffer(offer: hiredOffer),
                                8.toHeight,
                                OfferDetailsProviderTile(
                                    provider: hiredOffer.provider!),
                              ],
                            );
                          })
                        ],
                      ],
                    ),
                  );
                })),
          ),
          bottomNavigationBar: jd.jobDetailsModel.jobDetails == null
              ? null
              : Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                  decoration: BoxDecoration(
                      color: context.color.accentContrastColor,
                      border: Border(
                          top: BorderSide(
                              color: context.color.primaryBorderColor))),
                  child: Row(
                    children: [
                      Expanded(
                        flex: 1,
                        child: CustomButton(
                          onPressed: () {
                            if (jd.jobDetailsModel.isJobHired?.toString() !=
                                "0") {
                              LocalKeys.editNotAllowedAfterProviderHired
                                  .showToast();
                              return;
                            }
                            PostJobViewModel.dispose;
                            PostJobViewModel.instance.initJobEdit(context);
                            context.toPage(PostJobView());
                          },
                          btText: LocalKeys.editJob,
                        ),
                      ),
                      12.toWidth,
                      GestureDetector(
                        onTap: () {
                          final jdm = JobDetailsViewModel.instance;
                          jdm.tryDeletingJob(context);
                        },
                        child: SquircleContainer(
                          color: context.color.primaryWarningColor,
                          radius: 12,
                          padding: EdgeInsets.all(8),
                          child: SvgAssets.trash.toSVGSized(
                            24,
                            color: context.color.accentContrastColor,
                          ),
                        ),
                      )
                    ],
                  ),
                ));
    });
  }
}
