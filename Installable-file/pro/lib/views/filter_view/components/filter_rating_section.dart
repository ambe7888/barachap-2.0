import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/view_models/filter_view_model/filter_view_model.dart';

class FilterRatingSection extends StatelessWidget {
  const FilterRatingSection({super.key});

  @override
  Widget build(BuildContext context) {
    final fvm = FilterViewModel.instance;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FieldLabel(label: LocalKeys.rating),
        SquircleContainer(
          height: 44,
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 24),
          radius: 22,
          borderColor: context.color.primaryBorderColor,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              ValueListenableBuilder(
                valueListenable: fvm.ratingCount,
                builder: (context, rating, child) {
                  return RatingBar.builder(
                    initialRating: rating ?? 0,
                    unratedColor: context.color.tertiaryContrastColo,
                    itemSize: 32,
                    itemBuilder: (context, index) {
                      return Icon(
                        Icons.star_rounded,
                        color: context.color.primaryPendingColor,
                      );
                    },
                    onRatingUpdate: (value) {
                      fvm.ratingCount.value = value;
                    },
                  );
                },
              ),
              GestureDetector(
                onTap: () {
                  fvm.ratingCount.value = null;
                },
                child: Icon(
                  Icons.replay_rounded,
                  color: context.color.tertiaryContrastColo,
                ),
              )
            ],
          ),
        )
      ],
    );
  }
}
