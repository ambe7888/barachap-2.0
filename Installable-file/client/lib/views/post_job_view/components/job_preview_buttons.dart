import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/job_services/post_job_service.dart';
import '../../../view_models/job_list_view_model/job_list_view_model.dart';

class JobPreviewButtons extends StatelessWidget {
  const JobPreviewButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    final jlm = JobListViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => PostJobService(),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
                flex: 1,
                child: OutlinedButton(
                    onPressed: () {
                      context.pop;
                      PostJobViewModel.instance.resetPage(0);
                    },
                    child: Text(LocalKeys.edit))),
            12.toWidth,
            ValueListenableBuilder(
              valueListenable: pjm.isLoading,
              builder: (context, loading, child) {
                return Expanded(
                    flex: 1,
                    child: CustomButton(
                      onPressed: () async {
                        if (pjm.jobId != null) {
                          pjm.isLoading.value = true;
                          await Provider.of<PostJobService>(context,
                                  listen: false)
                              .tryEditingJob(pjm.jobId)
                              .then((v) {
                            pjm.isLoading.value = false;
                            if (v != true) return;
                            Provider.of<JobDetailsService>(context,
                                    listen: false)
                                .fetchOrderDetails(jobId: pjm.jobId);
                            LocalKeys.jobEditedSuccessfully.showToast();
                            context.pop;
                            context.pop;
                          });
                          jlm.refreshKey.currentState?.show();
                          return;
                        }
                        pjm.isLoading.value = true;
                        final postResult = await Provider.of<PostJobService>(
                                context,
                                listen: false)
                            .tryPostingJob();
                        if (postResult != true) {
                          pjm.isLoading.value = false;
                          return;
                        }
                        Alerts()
                            .showInfoDialogue(
                          context: context,
                          title: LocalKeys.congrats,
                          description: LocalKeys.jobPostingSuccessDescription,
                          infoAsset: SvgAssets.doneFilled.toSVGSized(80,
                              color: context.color.primarySuccessColor),
                        )
                            .then((_) {
                          context.pop;
                          context.pop;
                        });
                        jlm.refreshKey.currentState?.show();
                        pjm.isLoading.value = false;
                      },
                      btText: pjm.jobId != null
                          ? LocalKeys.saveChanges
                          : LocalKeys.postJob,
                      isLoading: loading,
                    ));
              },
            ),
          ],
        ),
      ),
    );
  }
}
