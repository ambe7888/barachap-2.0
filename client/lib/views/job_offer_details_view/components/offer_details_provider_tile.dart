import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/provider_model.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/pusher_helper.dart';
import '../../../helper/svg_assets.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../view_models/conversation_view_model/conversation_view_model.dart';
import '../../conversation_view/conversation_view.dart';
import '../../service_provider_view/service_provider_view.dart';

class OfferDetailsProviderTile extends StatelessWidget {
  final ProviderModel provider;
  const OfferDetailsProviderTile({super.key, required this.provider});

  @override
  Widget build(BuildContext context) {
    final myDetails = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails!;
    return GestureDetector(
      onTap: () {
        context.toPage(ServiceProviderView(
          providerID: provider.id,
        ));
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomNetworkImage(
              height: 48,
              width: 48,
              radius: 24,
              imageUrl: provider.image,
              fit: BoxFit.cover,
              name: provider.name,
              userPreloader: true,
            ),
            8.toWidth,
            Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text(
                          provider.name,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: context.titleMedium?.bold,
                        ),
                        4.toWidth,
                        if (provider.isVerified)
                          SvgAssets.verified.toSVGSized(20,
                              color: context.color.primarySuccessColor),
                      ],
                    ),
                    4.toHeight,
                    RichText(
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      text: TextSpan(
                          text: provider.serviceCategories?.firstOrNull?.name ==
                                  null
                              ? ""
                              : "${provider.serviceCategories?.firstOrNull?.name ?? ""} . ",
                          style: context.bodyMedium?.copyWith(
                              color: context.color.secondaryContrastColor),
                          children: [
                            TextSpan(
                                text: (provider.completionRate ?? 0) > 0
                                    ? "${provider.completionRate}% ${LocalKeys.completionRate}"
                                    : LocalKeys.noOrderCompletedYet)
                          ]),
                    ),
                    6.toHeight,
                    if ((provider.ratingCount ?? 0) > 0)
                      SquircleContainer(
                          padding: const EdgeInsets.symmetric(
                              vertical: 4, horizontal: 6),
                          color: context.color.mutedPendingColor,
                          radius: 8,
                          child: FittedBox(
                            child: Row(
                              children: [
                                Icon(
                                  Icons.star_rounded,
                                  size: 24,
                                  color: context.color.primaryPendingColor,
                                ),
                                4.toWidth,
                                Text(
                                  "${provider.avgRating.toStringAsFixed(1)} (${provider.ratingCount})",
                                  style: context.bodySmall
                                      ?.copyWith(
                                        color:
                                            context.color.primaryPendingColor,
                                      )
                                      .bold5,
                                ),
                              ],
                            ),
                          )),
                  ],
                )),
            8.toWidth,
            GestureDetector(
              onTap: () {
                ConversationViewModel.dispose;
                PusherHelper().connectToPusher(
                  context,
                  myDetails.id,
                  provider.id,
                );
                ConversationViewModel.instance.messageController.clear();
                context.toNamed(ConversationView.routeName, arguments: [
                  provider.id,
                  provider.name,
                  provider.image,
                  myDetails.id,
                ], then: () {
                  PusherHelper().disConnect();
                });
              },
              child: Container(
                padding: 10.paddingAll,
                decoration: BoxDecoration(
                    color: Colors.transparent,
                    shape: BoxShape.circle,
                    border: Border.all(color: primaryColor)),
                child:
                    SvgAssets.messageDots.toSVGSized(24, color: primaryColor),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
