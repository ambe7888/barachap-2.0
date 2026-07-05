import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/services/conversation_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:provider/provider.dart';

import '/views/conversation_view/components/conversations_input_box.dart';
// import '../../services/message_notification_count_service.dart';
import '../../utils/components/navigation_pop_icon.dart';
import 'components/conversation_message_list.dart';
import 'components/conversation_skeleton.dart';

class ConversationView extends StatefulWidget {
  static const routeName = "conversation_view";
  const ConversationView({super.key});

  @override
  State<ConversationView> createState() => _ConversationViewState();
}

class _ConversationViewState extends State<ConversationView> {
  bool _isSearching = false;
  final TextEditingController _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final arguments = context.arguments;
    final id = arguments[0];
    final name = arguments[1];
    final image = arguments[2];
    final myId = arguments[3];
    final cProvider = Provider.of<ConversationService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: _isSearching
            ? TextField(
                controller: _searchController,
                autofocus: true,
                decoration: InputDecoration(
                  hintText: "Rechercher",
                  border: InputBorder.none,
                  hintStyle: TextStyle(color: Colors.white70),
                ),
                style: const TextStyle(color: Colors.white),
                onChanged: (val) {
                  setState(() {});
                },
              )
            : Text(name),
        actions: [
          IconButton(
            icon: Icon(_isSearching ? Icons.close : Icons.search),
            onPressed: () {
              setState(() {
                if (_isSearching) {
                  _searchController.clear();
                }
                _isSearching = !_isSearching;
              });
            },
          ),
        ],
      ),
      body: Stack(
        children: [
          CustomFutureWidget(
              function: 2 == 2 ? cProvider.fetchConversationMessages(id) : null,
              shimmer: const ConversationSkeleton(),
              child:
                  Consumer<ConversationService>(builder: (context, cs, child) {
                return Column(
                  children: [
                    Expanded(
                        child: ConversationMessageList(
                      cs: cs,
                      name: name,
                      clientImage: image,
                    )),
                    ConversationInputBox(
                      providerId: id,
                    ),
                  ],
                );
              })),
        ],
      ),
    );
  }
}
