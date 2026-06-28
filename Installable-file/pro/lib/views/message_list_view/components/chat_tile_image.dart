import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_network_image.dart';

import '../../../app_static_values.dart';

class ChatTileImage extends StatelessWidget {
  final String? clientImage;
  final String? clientName;
  final bool isActive;
  final dynamic chatId;
  const ChatTileImage(
      {super.key,
      this.clientImage,
      required this.isActive,
      required this.clientName,
      this.chatId});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 58,
      width: 58,
      child: Stack(
        alignment: Alignment.bottomRight,
        children: [
          CustomNetworkImage(
            height: 58,
            width: 58,
            radius: 29,
            imageUrl: clientImage,
            fit: BoxFit.cover,
            name: clientName,
            color: chatAvatarBGColors[
                (int.tryParse(chatId.toString()) ?? Random().nextInt(1632)) %
                    chatAvatarBGColors.length],
            userPreloader: true,
          ),
          Padding(
            padding: 4.paddingAll,
            child: CircleAvatar(
              radius: 6,
              backgroundColor: isActive
                  ? context.color.primarySuccessColor
                  : context.color.mutedContrastColor,
            ),
          )
        ],
      ),
    );
  }
}
