import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../view_models/submit_review_view_model/submit_review_view_model.dart';

class SubmitReviewClient extends StatelessWidget {
  const SubmitReviewClient({super.key});

  @override
  Widget build(BuildContext context) {
    final srm = SubmitReviewViewModel.instance;
    return srm.orderNotifier.value?.client == null
        ? const SizedBox()
        : Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
              CustomNetworkImage(
                height: 48,
                width: 48,
                radius: 24,
                imageUrl: srm.orderNotifier.value?.client?.image,
                fit: BoxFit.cover,
                name: srm.orderNotifier.value?.client?.fullname,
                userPreloader: true,
              ),
              8.toWidth,
              Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        srm.orderNotifier.value?.client?.fullname ??
                            LocalKeys.na,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: context.titleMedium?.bold,
                      ),
                      4.toHeight,
                      Text(
                        "${LocalKeys.memberSince} ${DateFormat("yyyy").format(srm.orderNotifier.value?.client?.createdAt ?? DateTime.now())}",
                        style: context.bodySmall?.copyWith(
                            color: context.color.secondaryContrastColor),
                      ),
                    ],
                  )),
            ]),
          );
  }
}
