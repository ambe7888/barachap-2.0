import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

class IdentityVerifyTile extends StatelessWidget {
  final bool verifyStatus;
  const IdentityVerifyTile({super.key, required this.verifyStatus});

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        radius: 8,
        padding: 12.paddingAll,
        margin: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        borderColor: context.color.primaryBorderColor,
        child: Row(
          children: [
            CircleAvatar(
              radius: 24,
              backgroundColor: !verifyStatus
                  ? context.color.primaryPendingColor
                  : context.color.primarySuccessColor,
              child: Icon(
                !verifyStatus
                    ? Icons.hourglass_top_rounded
                    : Icons.done_rounded,
                color: context.color.accentContrastColor,
                size: 28,
              ),
            ),
            8.toWidth,
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(!verifyStatus ? LocalKeys.inProcess : LocalKeys.verified,
                    style: context.titleSmall?.bold),
                4.toHeight,
                Text(
                    !verifyStatus
                        ? LocalKeys.yourRequestIsBeingProcessed
                        : LocalKeys.yourIdHasBeenVerified,
                    style: context.bodySmall),
              ],
            )
          ],
        ));
  }
}
