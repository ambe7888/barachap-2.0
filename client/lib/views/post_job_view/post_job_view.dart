import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';

import 'components/post_job_basic_info.dart';
import 'components/post_job_budget_description.dart';
import 'components/post_job_button.dart';

class PostJobView extends StatelessWidget {
  PostJobView({super.key});

  final pages = [
    const PostJobBasicInfo(),
    const PostJobBudgetDescription(),
  ];

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: NavigationPopIcon(
          onTap: () {
            Alerts().confirmationAlert(
                context: context,
                title: LocalKeys.areYouSure,
                onConfirm: () async {
                  context.pop;
                  context.pop;
                });
          },
        ),
        title: Text(LocalKeys.postAJob),
      ),
      body: PageView.builder(
        controller: pjm.pageController,
        physics: const NeverScrollableScrollPhysics(),
        itemBuilder: (context, index) {
          return pages[index];
        },
        itemCount: pages.length,
      ),
      bottomNavigationBar: const PostJobButton(),
    );
  }
}
