import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/job_models/favorite_jobs_service.dart';
import 'package:prohand/views/job_details_view/job_details_view.dart';
import 'package:prohand/views/job_list_view/components/job_tile_date_type.dart';
import 'package:provider/provider.dart';

import '../../../helper/svg_assets.dart';
import '../../../models/job_models/job_list_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class JobTile extends StatelessWidget {
  final Job job;
  final bool hideBorder;
  const JobTile({super.key, required this.job, this.hideBorder = false});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toNamed(JobDetailsView.routeName, arguments: job.id);
      },
      child: SquircleContainer(
        margin: hideBorder ? null : 25.paddingH,
        padding: hideBorder ? 24.paddingH : 16.paddingAll,
        radius: hideBorder ? null : 8,
        borderColor: job.isApplied ? context.color.primarySuccessColor : context.color.primaryBorderColor,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (job.image != null && job.image!.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(right: 12.0),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(
                        job.image!,
                        height: 50,
                        width: 50,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) => const SizedBox(),
                      ),
                    ),
                  ),
                Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        job.title ?? "---",
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: context.titleMedium?.bold,
                      ),
                      if (job.isApplied)
                        Container(
                          margin: const EdgeInsets.only(top: 4),
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                          decoration: BoxDecoration(
                            color: context.color.primarySuccessColor.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(4),
                          ),
                          child: Text(
                            "Offer sent".tr(),
                            style: context.titleSmall?.copyWith(color: context.color.primarySuccessColor),
                          ),
                        ),
                    ],
                  ),
                ),
                12.toWidth,
                Consumer<FavoriteJobsService>(builder: (context, fj, child) {
                  final isFav = fj.isFavorite(job.id.toString());
                  return GestureDetector(
                    onTap: () {
                      if (isFav) {
                        fj.deleteFromFavorite(job.id.toString());
                        return;
                      }
                      fj.addToFavorite(job.id.toString(), job.toJson());
                    },
                    child: Container(
                      alignment: Alignment.center,
                      padding: 6.paddingAll,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border:
                            Border.all(color: context.color.primaryBorderColor),
                      ),
                      child: Icon(
                        isFav
                            ? Icons.favorite_rounded
                            : Icons.favorite_outline_rounded,
                        size: 20,
                        color: isFav
                            ? primaryColor
                            : context.color.tertiaryContrastColo,
                      ),
                    ),
                  );
                }),
              ],
            ),
            6.toHeight,
            JobTileDateType(
                date: job.createdAt ?? DateTime.now(),
                category: job.category ?? ""),
            Divider(
              color: context.color.primaryBorderColor,
              height: 24,
            ),
            Row(
              children: [
                Expanded(
                  flex: 1,
                  child: Text(
                    job.budget.cur,
                    style: context.titleSmall?.bold.copyWith(
                      color: primaryColor,
                    ),
                  ),
                ),
                if (job.jobLocation?.address != null)
                  ConstrainedBox(
                    constraints: BoxConstraints(maxWidth: context.width / 2),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        SvgAssets.mapPin.toSVGSized(
                          16,
                          color: context.color.tertiaryContrastColo,
                        ),
                        6.toWidth,
                        ConstrainedBox(
                          constraints: BoxConstraints(
                              maxWidth: (context.width / 2) - 22),
                          child: Text(
                            job.jobLocation?.address ?? "---",
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: context.titleSmall?.bold.copyWith(
                              color: context.color.tertiaryContrastColo,
                            ),
                          ),
                        ),
                      ],
                    ),
                  )
              ],
            )
          ],
        ),
      ),
    );
  }
}
