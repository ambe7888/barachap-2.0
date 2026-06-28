import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/job_list_view/components/job_tile_skeleton.dart';

class JobListSkeleton extends StatelessWidget {
  const JobListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      physics: const NeverScrollableScrollPhysics(),
      padding: const EdgeInsets.only(top: 16),
      child: const Wrap(
        runSpacing: 16,
        children: [
          JobTileSkeleton(),
          JobTileSkeleton(),
          JobTileSkeleton(),
          JobTileSkeleton(),
          JobTileSkeleton(),
          JobTileSkeleton(),
        ],
      ).shim,
    );
  }
}
