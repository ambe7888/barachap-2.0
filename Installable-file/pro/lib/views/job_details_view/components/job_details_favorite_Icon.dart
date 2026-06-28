import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../models/job_models/favorite_jobs_service.dart';
import '../../../models/job_models/job_list_model.dart';
import '../../../services/job_services/job_details_service.dart';

class JobDetailsFavoriteIcon extends StatelessWidget {
  final dynamic id;
  const JobDetailsFavoriteIcon({super.key, required this.id});

  @override
  Widget build(BuildContext context) {
    return Consumer<JobDetailsService>(builder: (context, jd, child) {
      if (jd.jobDetailsModel.jobDetails == null) {
        return const SizedBox();
      }
      final jobDetails = jd.jobDetailsModel.jobDetails!;
      return Consumer<FavoriteJobsService>(builder: (context, fj, child) {
        final isFav = fj.isFavorite(id.toString());
        return GestureDetector(
          onTap: () {
            if (isFav) {
              fj.deleteFromFavorite(id.toString());
              return;
            }
            fj.addToFavorite(
                id.toString(),
                Job(
                  id: jobDetails.id,
                  title: jobDetails.title,
                  budget: jobDetails.budget,
                  category: jobDetails.category,
                  jobLocation: jobDetails.jobLocation,
                ).toJson());
          },
          child: Container(
            alignment: Alignment.center,
            padding: 6.paddingAll,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              border: Border.all(color: context.color.primaryBorderColor),
            ),
            child: Icon(
              isFav ? Icons.favorite_rounded : Icons.favorite_outline_rounded,
              size: 20,
              color: isFav ? primaryColor : context.color.tertiaryContrastColo,
            ),
          ),
        );
      });
    });
  }
}
