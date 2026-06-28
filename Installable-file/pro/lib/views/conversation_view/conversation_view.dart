import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/conversation_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:provider/provider.dart';

import '/views/conversation_view/components/conversations_input_box.dart';
// import '../../services/message_notification_count_service.dart';
import '../../models/messages/chat_list_model.dart';
import '../../utils/components/navigation_pop_icon.dart';
import 'components/conversation_message_list.dart';
import 'components/conversation_skeleton.dart';

class ConversationView extends StatelessWidget {
  static const routeName = "conversation_view";
  const ConversationView({super.key});

  @override
  Widget build(BuildContext context) {
    final arguments = context.arguments;
    final ChatModel? chat = arguments;
    final cProvider = Provider.of<ConversationService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(chat?.clientName ?? "---"),
      ),
      body: Stack(
        children: [
          CustomFutureWidget(
              function: 2 == 2
                  ? cProvider.fetchConversationMessages(chat?.clientId)
                  : null,
              shimmer: const ConversationSkeleton(),
              child:
                  Consumer<ConversationService>(builder: (context, cs, child) {
                return Column(
                  children: [
                    Expanded(
                        child: ConversationMessageList(
                      cs: cs,
                      name: chat?.clientName,
                      clientImage: chat?.clientImage,
                      providerImage: chat?.providerImage,
                      providerName: chat?.providerName,
                    )),
                    ConversationInputBox(
                      clientId: chat?.clientId,
                    ),
                  ],
                );
              })),
        ],
      ),
    );
  }
}
