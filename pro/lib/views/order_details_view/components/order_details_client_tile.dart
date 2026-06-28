import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/order_services/order_details_service.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../helper/pusher_helper.dart';
import '../../../helper/svg_assets.dart';
import '../../../models/messages/chat_list_model.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../view_models/conversation_view_model/conversation_view_model.dart';
import '../../conversation_view/conversation_view.dart';

class OrderDetailsClientTile extends StatelessWidget {
  const OrderDetailsClientTile({super.key});

  @override
  Widget build(BuildContext context) {
    final client = Provider.of<OrderDetailsService>(context, listen: false)
        .orderDetailsModel
        .orderDetails
        ?.client;
    final myDetails = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails!;
    return client == null
        ? const SizedBox()
        : Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CustomNetworkImage(
                  height: 48,
                  width: 48,
                  radius: 24,
                  imageUrl: client.image,
                  fit: BoxFit.cover,
                  name: client.fullname,
                  userPreloader: true,
                ),
                8.toWidth,
                Expanded(
                    flex: 1,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          client.fullname ?? LocalKeys.na,
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: context.titleMedium?.bold,
                        ),
                        4.toHeight,
                        if (client.reviewCount > 0)
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
                                      "${client.averageRating.toStringAsFixed(1)} (${client.reviewCount})",
                                      style: context.bodySmall
                                          ?.copyWith(
                                            color: context
                                                .color.primaryPendingColor,
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
                      client.id,
                    );
                    ConversationViewModel.instance.messageController.clear();
                    context.toNamed(ConversationView.routeName,
                        arguments: ChatModel(
                            clientUnseenMsgCount: 0,
                            providerUnseenMsgCount: 0,
                            clientId: client.id,
                            clientImage: client.image,
                            clientName: client.fullname,
                            providerId: myDetails.id,
                            providerImage: myDetails.image), then: () {
                      PusherHelper().disConnect();
                    });
                  },
                  child: Container(
                    padding: 10.paddingAll,
                    decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(color: primaryColor)),
                    child: SvgAssets.messageDots
                        .toSVGSized(24, color: primaryColor),
                  ),
                ),
              ],
            ),
          );
  }
}
