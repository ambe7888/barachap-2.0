import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/profile_services/profile_info_service.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:provider/provider.dart';

import '../../../helper/pusher_helper.dart';
import '../../../models/service/service_details_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../view_models/conversation_view_model/conversation_view_model.dart';
import '../../conversation_view/conversation_view.dart';

class ServiceDetailsProvider extends StatelessWidget {
  final ServiceDetailsModel sd;

  const ServiceDetailsProvider({super.key, required this.sd});

  @override
  Widget build(BuildContext context) {
    final myDetails = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails;
    final serviceDetails = sd.allServices!;
    final name = serviceDetails.admin?.name ??
        (serviceDetails.provider?.name.trim().isEmpty ?? true
            ? null
            : serviceDetails.provider?.name);
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        CustomNetworkImage(
          height: 48,
          width: 48,
          radius: 24,
          imageUrl:
              serviceDetails.admin?.image ?? serviceDetails.provider?.image,
          fit: BoxFit.cover,
          name: name ?? serviceDetails.provider?.email,
          userPreloader: true,
        ),
        8.toWidth,
        Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Wrap(
                  spacing: 6,
                  children: [
                    Text(
                      name ??
                          serviceDetails.provider?.email?.obscureEmail ??
                          "---",
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: context.titleMedium?.bold,
                    ),
                    if (sd.allServices?.provider?.isVerified ?? true)
                      SvgAssets.verified.toSVGSized(20,
                          color: context.color.primarySuccessColor),
                    if ((serviceDetails.provider?.ratingCount ?? 0) > 0)
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
                                  "${serviceDetails.provider?.avgRating.toStringAsFixed(1)} (${serviceDetails.provider?.ratingCount?.round()})",
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
                ),
                4.toHeight,
                RichText(
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  text: TextSpan(
                      text: serviceDetails.provider?.serviceCategories
                                  ?.firstOrNull?.name ==
                              null
                          ? null
                          : "${serviceDetails.provider?.serviceCategories?.firstOrNull?.name} . ",
                      style: context.bodyMedium?.copyWith(
                          color: context.color.secondaryContrastColor),
                      children: [
                        TextSpan(
                          text: (serviceDetails.provider?.completionRate ?? 0) >
                                  0
                              ? "${serviceDetails.provider!.completionRate}% ${LocalKeys.completionRate}"
                              : LocalKeys.noOrderCompletedYet,
                        )
                      ]),
                ),
              ],
            )),
        8.toWidth,
        if (serviceDetails.provider != null && myDetails != null)
          GestureDetector(
            onTap: () {
              ConversationViewModel.dispose;
              final provider = serviceDetails.provider;
              PusherHelper().connectToPusher(
                context,
                myDetails.id,
                provider?.id,
              );
              ConversationViewModel.instance.messageController.clear();
              context.toNamed(ConversationView.routeName, arguments: [
                provider?.id,
                provider?.name,
                provider?.image,
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
              child: SvgAssets.messageDots.toSVGSized(24, color: primaryColor),
            ),
          ),
      ],
    );
  }
}
