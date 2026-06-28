import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_network_image.dart';

import '../../../helper/constant_helper.dart';

class ServiceDetailsRatingTile extends StatelessWidget {
  final String userImage;
  final String userName;
  final num rating;
  final String? description;
  final DateTime? createdAt;

  const ServiceDetailsRatingTile({
    super.key,
    required this.userImage,
    required this.userName,
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
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CustomNetworkImage(
                height: 44,
                width: 44,
                radius: 8,
                imageUrl: userImage,
                name: userName,
                fit: BoxFit.cover,
                userPreloader: true,
              ),
              8.toWidth,
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    userName,
                    style: context.titleSmall?.bold,
                  ),
                  6.toHeight,
                  Row(
                    children: [
                      RatingBar.builder(
                          initialRating: rating.toDouble(),
                          ignoreGestures: true,
                          itemSize: 18,
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
                  )
                ],
              ),
            ],
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
