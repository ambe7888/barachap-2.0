import 'package:flutter/material.dart';
import 'package:path/path.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/view_models/conversation_view_model/conversation_view_model.dart';

import '/helper/extension/context_extension.dart';
import '/helper/local_keys.g.dart';
import '../../../utils/components/custom_preloader.dart';

class ConversationInputBox extends StatelessWidget {
  final clientId;
  const ConversationInputBox({super.key, this.clientId});

  @override
  Widget build(BuildContext context) {
    final cm = ConversationViewModel.instance;
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ValueListenableBuilder(
            valueListenable: cm.aFile,
            builder: (context, value, child) {
              return value == null
                  ? const SizedBox()
                  : Container(
                      margin: const EdgeInsets.only(bottom: 12),
                      child: Text(
                        basename(value.path),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: context.bodySmall,
                      ),
                    );
            },
          ),
          Row(
            children: [
              Expanded(
                flex: 1,
                child: TextFormField(
                  controller: cm.messageController,
                  textInputAction: TextInputAction.newline,
                  minLines: 1,
                  maxLines: 100,
                  decoration: InputDecoration(
                    hintText: LocalKeys.message,
                  ),
                ),
              ),
              12.toWidth,
              ValueListenableBuilder(
                valueListenable: cm.aFile,
                builder: (context, file, child) => GestureDetector(
                  onTap: () async {
                    cm.attachFile();
                  },
                  child: (file != null ? SvgAssets.trash : SvgAssets.gallery)
                      .toSVGSized(24,
                          color: context.color.tertiaryContrastColo),
                ),
              ),
              12.toWidth,
              ValueListenableBuilder(
                valueListenable: cm.loading,
                builder: (context, loading, child) => GestureDetector(
                  onTap: () async {
                    cm.trySendingMessage(context, clientId);
                  },
                  child: loading
                      ? const CustomPreloader()
                      : SvgAssets.send.toSVGSized(24,
                          color: context.color.tertiaryContrastColo),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
