import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/views/job_list_view/components/job_tile.dart';

import '../../../models/job_models/job_list_model.dart';

class JobTileSheet extends StatelessWidget {
  final List<Job> jobs;
  const JobTileSheet({super.key, required this.jobs});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        SingleChildScrollView(
          child: Column(
            children: [
              Padding(
                padding: 16.paddingV,
                child: Align(
                  alignment: Alignment.center,
                  child: Container(
                    height: 4,
                    width: 48,
                    margin: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(12),
                      color: context.color.mutedContrastColor,
                    ),
                  ),
                ),
              ),
              Wrap(
                runSpacing: 12,
                children: jobs
                    .map((job) => JobTile(
                          job: job,
                        ))
                    .toList(),
              ),
              20.toHeight,
            ],
          ),
        ),
        if (jobs.length > 3)
          Positioned(
            bottom: 32,
            right: 0,
            child: GestureDetector(
              onTap: () {
                context.pop;
              },
              child: Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                margin: const EdgeInsets.symmetric(vertical: 26),
                decoration: BoxDecoration(
                    borderRadius: const BorderRadius.only(
                      topLeft: Radius.circular(12),
                      bottomLeft: Radius.circular(12),
                    ),
                    color: context.color.accentContrastColor,
                    border:
                        Border.all(color: context.color.primaryBorderColor)),
                child: Transform.rotate(
                    angle: context.dProvider.textDirectionRight ? pi : 0,
                    child: const Icon(Icons.chevron_left_outlined)),
              ),
            ),
          ),
      ],
    );
  }
}
