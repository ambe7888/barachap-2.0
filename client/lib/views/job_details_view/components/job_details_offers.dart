import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/views/offer_list_view/offer_list_view.dart';

import '../../../app_static_values.dart';
import '../../../helper/local_keys.g.dart';
import '../../../models/job/job_details_model.dart';

class JobDetailsOffers extends StatelessWidget {
  final List<JobOffer> offers;
  const JobDetailsOffers({super.key, required this.offers});

  @override
  Widget build(BuildContext context) {
    Set providerSet = offers.toSet();
    int remainingProviders = offers.length - 6;
    if (offers.length > 6) {
      providerSet = offers.sublist(0, 7).toSet();
    }
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.offers,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.bodySmall?.copyWith(
              color: context.color.tertiaryContrastColo,
            ),
          ),
          6.toHeight,
          GestureDetector(
            onTap: () {
              context.toNamed(
                OfferListView.routeName,
                arguments: offers.firstOrNull?.jobPostId,
              );
            },
            child: Row(
              children: [
                Expanded(
                  flex: 1,
                  child: Stack(
                    children: [
                      ...List.generate(
                        providerSet.toList().length,
                        (index) => Container(
                          margin: EdgeInsets.only(
                            left:
                                context.dProvider.textDirectionRight
                                    ? 0
                                    : (30 * index).toDouble(),
                            right:
                                context.dProvider.textDirectionRight
                                    ? (30 * index).toDouble()
                                    : 0,
                          ),
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(
                              color: context.color.accentContrastColor,
                              width: 4,
                            ),
                          ),
                          child: CustomNetworkImage(
                            height: 36,
                            width: 36,
                            fit: BoxFit.cover,
                            radius: 16,
                            imageUrl: offers[index].provider?.image,
                            name: offers[index].provider?.name,
                            color:
                                chatAvatarBGColors[(int.tryParse(
                                          offers[index].id.toString(),
                                        ) ??
                                        Random().nextInt(1632)) %
                                    chatAvatarBGColors.length],
                            userPreloader: true,
                          ),
                        ),
                      ),
                      if (remainingProviders > 0)
                        Container(
                          height: 40,
                          width: 40,
                          margin: EdgeInsets.only(
                            left:
                                context.dProvider.textDirectionRight
                                    ? 0
                                    : (30 * 7).toDouble(),
                            right:
                                context.dProvider.textDirectionRight
                                    ? (30 * 7).toDouble()
                                    : 0,
                          ),
                          padding: const EdgeInsets.all(2),
                          alignment: Alignment.center,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: primaryColor,
                            border: Border.all(
                              color: context.color.accentContrastColor,
                              width: 4,
                              strokeAlign: BorderSide.strokeAlignCenter,
                            ),
                          ),
                          child: Text(
                            "$remainingProviders+",
                            style: context.titleMedium?.bold.copyWith(
                              color: context.color.accentContrastColor,
                            ),
                          ),
                        ),
                    ],
                  ),
                ),
                Container(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: [
                      Text(
                        LocalKeys.view,
                        maxLines: 1,
                        style: context.titleMedium?.bold.copyWith(height: 2.5),
                      ),
                      IconButton(
                        onPressed: () {
                          debugPrint(offers.firstOrNull?.jobPostId.toString());
                          context.toNamed(
                            OfferListView.routeName,
                            arguments: offers.firstOrNull?.jobPostId,
                          );
                        },
                        icon: Icon(
                          Icons.chevron_right_outlined,
                          color: context.color.primaryContrastColor,
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
