import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../helper/constant_helper.dart';

class RatingTile extends StatelessWidget {
  final String title;
  final num rating;
  final String? description;
  final DateTime? createdAt;

  const RatingTile({
    super.key,
    required this.title,
    required this.rating,
    this.description,
    this.createdAt,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              RatingBar.builder(
                  initialRating: rating.toDouble(),
                  ignoreGestures: true,
                  itemSize: 20,
                  allowHalfRating: true,
                  unratedColor: context.color.mutedContrastColor,
                  itemBuilder: (context, index) {
                    return Icon(Icons.star_rounded,
                        color: context.color.primaryPendingColor);
                  },
                  onRatingUpdate: (_) {}),
              4.toWidth,
              Text(rating.toStringAsFixed(1),
                  style: context.bodySmall?.bold.copyWith(
                    color: context.color.tertiaryContrastColo,
                  ))
            ],
          ),
          6.toHeight,
          Text(
            title,
            style: context.titleSmall?.bold,
          ),
          if (description != null) ...[
            8.toHeight,
            Text(
              description!,
              style: context.bodyMedium,
            )
          ],
          if (createdAt != null) ...[
            8.toHeight,
            Text(
              DateFormat("dd MMM yyyy", dProvider.languageSlug)
                  .format(createdAt!),
              style: context.bodySmall?.bold,
            )
          ],
        ],
      ),
    );
  }
}
