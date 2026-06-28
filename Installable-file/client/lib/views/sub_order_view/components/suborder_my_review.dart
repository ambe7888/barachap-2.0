import 'package:flutter/material.dart';
import 'package:flutter_rating_bar/flutter_rating_bar.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:provider/provider.dart';
import 'package:readmore/readmore.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../services/profile_services/profile_info_service.dart';

class SuborderMyReview extends StatelessWidget {
  final SubOrder so;
  const SuborderMyReview({super.key, required this.so});

  @override
  Widget build(BuildContext context) {
    final myId = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails
        ?.id;
    final reviews = so.reviews;
    Review? myReview;
    bool alreadyReviewed = false;
    for (Review re in reviews!) {
      if (re.reviewerId?.toString() == myId.toString() &&
          re.subOrderId?.toString() == so.id.toString()) {
        alreadyReviewed = true;
        myReview = re;
        break;
      }
    }
    return !alreadyReviewed
        ? const SizedBox()
        : Container(
            color: context.color.accentContrastColor,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            margin: const EdgeInsets.only(top: 8),
            width: double.infinity,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.myReview,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                6.toHeight,
                RatingBar.builder(
                    initialRating: myReview!.rating.toDouble(),
                    itemSize: 24,
                    allowHalfRating: false,
                    itemBuilder: (context, index) {
                      return Icon(
                        Icons.star_rounded,
                        size: 16,
                        color: context.color.primaryPendingColor,
                      );
                    },
                    onRatingUpdate: (v) {}),
                4.toHeight,
                ReadMoreText(
                  myReview.message!,
                  trimMode: TrimMode.Line,
                  trimLines: 3,
                  colorClickableText: primaryColor,
                  trimCollapsedText: LocalKeys.showMore,
                  trimExpandedText: LocalKeys.showLess,
                  style: context.bodyMedium,
                ),
                4.toHeight,
                Text(
                  timeago.format(myReview.createdAt ?? DateTime.now(),
                      locale: context.dProvider.languageSlug),
                  style: context.bodySmall,
                ),
              ],
            ),
          );
  }
}
