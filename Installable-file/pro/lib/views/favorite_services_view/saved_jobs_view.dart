import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';

import '../../models/job_models/job_list_model.dart';
import '../job_list_view/components/job_tile.dart';

class SavedJobsView extends StatelessWidget {
  const SavedJobsView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.savedJobs),
      ),
      body: Column(
        children: [
          const SizedBox().divider,
          Expanded(
            child: Scrollbar(
                child: CustomScrollView(
              slivers: [
                16.toHeight.toSliver,
                SliverList.separated(
                  itemBuilder: (context, index) {
                    return JobTile(
                        job: Job(
                      id: index,
                      title:
                          "I need someone to fix my staircase that made out of stainless steel.",
                      budget: 236,
                    ));
                  },
                  separatorBuilder: (context, index) => 12.toHeight,
                  itemCount: 20,
                ),
                16.toHeight.toSliver,
                const ScrollPreloader(loading: false).toSliver,
              ],
            )),
          )
        ],
      ),
    );
  }
}
