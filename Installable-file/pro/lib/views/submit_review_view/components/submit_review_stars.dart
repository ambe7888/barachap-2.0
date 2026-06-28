import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/field_label.dart';

import '../../../view_models/submit_review_view_model/submit_review_view_model.dart';

class SubmitReviewStars extends StatelessWidget {
  const SubmitReviewStars({super.key});

  @override
  Widget build(BuildContext context) {
    final srm = SubmitReviewViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      width: double.infinity,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          FieldLabel(label: LocalKeys.overallRatingForCustomer),
          ValueListenableBuilder(
            valueListenable: srm.ratingCountNotifier,
            builder: (context, rating, child) => RatingBar.builder(
                initialRating: rating,
                itemSize: (context.width - 112) / 5,
                itemBuilder: (context, index) {
                  return SquircleContainer(
                      radius: 8,
                      padding: 12.paddingAll,
                      borderColor: context.color.primaryBorderColor,
                      margin: 8.paddingH,
                      child: Icon(
                        Icons.star_rounded,
                        size: 24,
                        color: context.color.primaryPendingColor,
                      ));
                },
                onRatingUpdate: (v) {
                  srm.ratingCountNotifier.value = v;
                }),
          )
        ],
      ),
    );
  }
}
