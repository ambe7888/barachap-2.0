import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/job_services/job_details_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/job_details_view_model/job_details_view_model.dart';
import 'package:prohand/views/job_details_view/components/job_details_address.dart';
import 'package:prohand/views/job_details_view/components/job_details_date_schedule.dart';
import 'package:prohand/views/job_details_view/components/job_details_description.dart';
import 'package:prohand/views/job_details_view/components/job_details_favorite_Icon.dart';
import 'package:provider/provider.dart';

import 'components/job_details_buttons.dart';
import 'components/job_details_client_tile.dart';
import 'components/job_details_gellary.dart';
import 'components/job_details_offer.dart';
import 'components/job_details_skeleton.dart';
import 'components/job_details_title_budget.dart';

class JobDetailsView extends StatelessWidget {
  static const routeName = "job_details_view";
  const JobDetailsView({super.key});

  @override
  Widget build(BuildContext context) {
    final id = context.arguments;
    final jdProvider = Provider.of<JobDetailsService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.jobDetails),
        actions: [
          JobDetailsFavoriteIcon(id: id),
          16.toWidth,
        ],
      ),
      body: CustomRefreshIndicator(
        key: JobDetailsViewModel.instance.refreshKey,
        onRefresh: () async {
          await jdProvider.fetchJobDetails(id, refresh: true);
        },
        child: CustomFutureWidget(
          function: jdProvider.shouldAutoFetch(id)
              ? jdProvider.fetchJobDetails(id)
              : null,
          shimmer: const JobDetailsSkeleton(),
          child: Consumer<JobDetailsService>(builder: (context, jd, child) {
            if (jd.jobDetailsModel.jobDetails == null) {
              return EmptyWidget(title: LocalKeys.jobDetails);
            }
            final jobDetails = jd.jobDetailsModel.jobDetails!;
            return Column(
              children: [
                Expanded(
                  child: SingleChildScrollView(
                    padding: 8.paddingV,
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        JobDetailsTitleBudget(
                          title: jobDetails.title ?? "--",
                          budget: jobDetails.budget,
                          createdAt: jobDetails.createdAt ?? DateTime.now(),
                          category: jobDetails.category ?? "---",
                        ),
                        8.toHeight,
                        JobDetailsDateSchedule(
                            date: jobDetails.date ?? DateTime.now(),
                            schedule: jobDetails.time ?? "---"),
                        8.toHeight,
                        JobDetailsAddress(address: jobDetails.jobLocation),
                        8.toHeight,
                        JobDetailsDescription(
                            description: jobDetails.description ?? "---"),
                        if (jobDetails.galleryImages.isNotEmpty) ...[
                          8.toHeight,
                          JobDetailsGallery(gallery: jobDetails.galleryImages)
                        ],
                        8.toHeight,
                        JobDetailsClientTile(jobDetails: jobDetails),
                        8.toHeight,
                        JobDetailsOffer(
                            jobDetails: jobDetails,
                            jobDetailsModel: jd.jobDetailsModel),
                      ],
                    ),
                  ),
                ),
                const JobDetailsButtons(),
              ],
            );
          }),
        ),
      ),
    );
  }
}
