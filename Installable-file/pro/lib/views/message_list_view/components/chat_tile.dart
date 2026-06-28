import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeAgo;

import '../../../models/messages/chat_list_model.dart';
import '../../../services/chat_services/chat_list_service.dart';
import 'chat_tile_image.dart';

class ChatTile extends StatelessWidget {
  final ChatModel chat;
  const ChatTile({super.key, required this.chat});

  @override
  Widget build(BuildContext context) {
    final haveUnreadMessage = chat.providerUnseenMsgCount > 0;
    final clProvider = Provider.of<ChatListService>(context, listen: false);
    bool isActive = false;

    try {
      isActive = clProvider.chatListModel.activityCheck
          .containsKey(chat.clientId.toString());
    } catch (e) {
      debugPrint(e.toString());
    }
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      padding: 16.paddingV,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          ChatTileImage(
            chatId: chat.id,
            clientImage: chat.clientImage,
            clientName: chat.clientName,
            isActive: isActive,
          ),
          8.toWidth,
          Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    chat.clientName ?? "---",
                    style: context.titleMedium?.bold.copyWith(
                        color: haveUnreadMessage ? primaryColor : null),
                  ),
                  if (chat.lastMessage != null) ...[
                    8.toHeight,
                    Row(
                      children: [
                        Expanded(
                          flex: 2,
                          child: Text(
                            (chat.lastMessage?.fromUser.toString() == "2"
                                    ? "${LocalKeys.you}: "
                                    : "") +
                                (chat.lastMessage!.messageText ??
                                    LocalKeys.file),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: context.bodySmall?.copyWith(
                                color: haveUnreadMessage
                                    ? primaryColor
                                    : context.color.tertiaryContrastColo),
                          ),
                        ),
                        12.toWidth,
                        Text(
                          timeAgo.format(
                              chat.lastMessage?.createdAt ?? DateTime.now(),
                              locale: context.dProvider.languageSlug),
                          style: context.bodySmall?.copyWith(
                              color: haveUnreadMessage
                                  ? primaryColor
                                  : context.color.tertiaryContrastColo),
                        ),
                      ],
                    )
                  ]
                ],
              ))
        ],
      ),
    );
  }
}
