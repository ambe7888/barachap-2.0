import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/view_models/conversation_view_model/conversation_view_model.dart';

import '../../../models/conversation_model.dart';
import '../../../services/conversation_service.dart';
import '../../../utils/components/scrolling_preloader.dart';
import 'chat_bubble.dart';

class ConversationMessageList extends StatelessWidget {
  final ConversationService cs;
  final clientImage;
  final providerImage;
  final name;
  final providerName;
  const ConversationMessageList({
    super.key,
    required this.cs,
    required this.clientImage,
    required this.providerImage,
    required this.name,
    required this.providerName,
  });

  @override
  Widget build(BuildContext context) {
    final cm = ConversationViewModel.instance;
    cm.scrollController.addListener(() {
      cm.tryLoadingMore(context);
    });
    return (cs.conversationModel.allMessage?.length ?? 0) <= 0
        ? ListView()
        : ListView.separated(
            controller: cm.scrollController,
            padding: const EdgeInsets.all(20),
            reverse: true,
            itemBuilder: (context, index) {
              if ((cs.nextPage != null && !cs.nexLoadingFailed) &&
                  index == cs.conversationModel.allMessage!.length) {
                return ScrollPreloader(
                  loading: cs.nextPageLoading,
                  text: LocalKeys.pullDown,
                  iconData: Icons.arrow_circle_down_rounded,
                );
              }
              MessageModel? previousMessage;
              if (index != 0) {
                previousMessage = cs.conversationModel.allMessage![index - 1];
              }
              final datum = cs.conversationModel.allMessage![index];
              final bool senderFromWeb = datum.fromUser.toString() != "2";
              return ChatBubble(
                  datum: datum,
                  senderFromWeb: senderFromWeb,
                  clientImage: senderFromWeb ? clientImage : providerImage,
                  name: senderFromWeb ? name : providerName,
                  index: index,
                  sameUser: previousMessage?.fromUser.toString() ==
                      datum.fromUser.toString());
            },
            separatorBuilder: (context, index) => 8.toHeight,
            itemCount: cs.conversationModel.allMessage!.length +
                (cs.nextPage != null && !cs.nexLoadingFailed ? 1 : 0));
  }
}
