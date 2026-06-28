import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/job_models/favorite_jobs_service.dart';
import 'package:provider/provider.dart';

import '../../../utils/components/empty_widget.dart';
import 'job_tile.dart';

class SavedJobList extends StatelessWidget {
  const SavedJobList({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<FavoriteJobsService>(builder: (context, fj, child) {
      if (fj.jobs.isEmpty) {
        return SizedBox(
          height: 400,
          child: EmptyWidget(
            title: LocalKeys.noJobsFound,
            physics: const NeverScrollableScrollPhysics(),
          ),
        );
      }
      return Wrap(
        runSpacing: 16,
        children: fj.jobs.map((job) {
          return JobTile(
            job: job,
          );
        }).toList(),
      );
    });
  }
}
