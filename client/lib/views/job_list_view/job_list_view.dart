import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/jobs/job_list_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';
import 'package:provider/provider.dart';

import '../../services/profile_services/profile_info_service.dart';
import '../../utils/components/scrolling_preloader.dart';
import '../../view_models/job_list_view_model/job_list_view_model.dart';
import '../account_skeleton/account_skeleton.dart';
import '../post_job_view/post_job_view.dart';
import 'components/job_list_skeleton.dart';
import 'components/job_tile.dart';

class JobListView extends StatelessWidget {
  const JobListView({super.key});

  @override
  Widget build(BuildContext context) {
    final jlm = JobListViewModel.instance;
    final jlProvider = Provider.of<JobListService>(context, listen: false);
    jlm.scrollController.addListener(() {
      jlm.tryToLoadMore(context);
    });
    return Consumer<ProfileInfoService>(builder: (context, pi, child) {
      return Scaffold(
        backgroundColor: context.color.backgroundColor,
        appBar: AppBar(
          title: Text(LocalKeys.allJobs),
        ),
        body: CustomRefreshIndicator(
          onRefresh: () async {
            await jlProvider.fetchJobList();
          },
          child: Scrollbar(
              controller: jlm.scrollController,
              child: Consumer<JobListService>(builder: (context, jl, child) {
                if (pi.profileInfoModel.userDetails == null) {
                  return const AccountSkeleton();
                }
                return CustomRefreshIndicator(
                  refreshKey: jlm.refreshKey,
                  onRefresh: () async {
                    await jl.fetchJobList(refresh: true);
                  },
                  child: CustomFutureWidget(
                     function: jl.shouldAutoFetch ? jl.fetchJobList() : null,
                    shimmer: const JobListSkeleton(),
                    child: jl.jobListModel.jobs?.isEmpty ?? true
                        ? EmptyWidget(title: LocalKeys.noJobFound)
                        : CustomScrollView(
                            physics: const AlwaysScrollableScrollPhysics(),
                            controller: jlm.scrollController,
                            slivers: [
                               SliverList.separated(
                                 itemBuilder: (context, index) {
                                   final job = jl.jobListModel.jobs![index];
                                   return JobTile(job: job);
                                 },
                                 separatorBuilder: (context, index) => const SizedBox(),
                                 itemCount: jl.jobListModel.jobs?.length ?? 0,
                               ),
                              24.toHeight.toSliver,
                              if (jl.nextPage != null &&
                                  !jl.nexLoadingFailed) ...[
                                ScrollPreloader(
                                  loading: jl.nextPageLoading,
                                ).toSliver,
                                24.toHeight.toSliver
                              ],
                            ],
                          ),
                  ),
                );
              })),
        ),
        floatingActionButton: pi.profileInfoModel.userDetails == null
            ? null
            : GestureDetector(
                onTap: () {
                  PostJobViewModel.dispose;
                  context.toPage(PostJobView());
                },
                child: SquircleContainer(
                  color: primaryColor,
                  radius: 24,
                  padding:
                      const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                  child: FittedBox(
                    child: Row(
                      children: [
                        SvgAssets.addJob.toSVGSized(24,
                            color: context.color.accentContrastColor),
                        4.toWidth,
                        Text(
                          LocalKeys.postAJob,
                          style: context.titleSmall?.copyWith(
                              color: context.color.accentContrastColor),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
      );
    });
  }
}
