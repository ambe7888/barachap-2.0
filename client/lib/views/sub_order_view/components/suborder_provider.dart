import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/models/provider_model.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:provider/provider.dart';

import '../../../helper/pusher_helper.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../../view_models/conversation_view_model/conversation_view_model.dart';
import '../../conversation_view/conversation_view.dart';
import '../../service_provider_view/service_provider_view.dart';

class SuborderProvider extends StatelessWidget {
  final ProviderModel provider;

  const SuborderProvider({super.key, required this.provider});

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
        color: context.color.accentContrastColor,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
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
                        text: (provider.orderCompleted) <= 0
                            ? "${provider.completionRate}% ${LocalKeys.completionRate}"
                            : LocalKeys.noOrderCompletedYet,
                        style: context.bodyMedium?.copyWith(
                            color: context.color.secondaryContrastColor),
                      ),
                    ),
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
                    shape: BoxShape.circle,
                    color: Colors.transparent,
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
