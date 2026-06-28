import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../models/withdraw_history_model.dart';
import 'expansion_children.dart';
import 'expansion_title.dart';

class WithdrawHistoryCard extends StatelessWidget {
  final History history;
  final String? path;
  const WithdrawHistoryCard({
    super.key,
    required this.history,
    required this.path,
  });

  @override
  Widget build(BuildContext context) {
    final ExpansionTileController controller = ExpansionTileController();
    return SquircleContainer(
      radius: 10,
      borderColor: context.color.primaryBorderColor,
      margin: 24.paddingH,
      child: ExpansionTile(
        backgroundColor: context.color.accentContrastColor,
        tilePadding: EdgeInsets.only(
            right: 20,
            left: 20,
            top: 20,
            bottom: ((history.note ?? history.image) == null) ? 20 : 0),
        childrenPadding: ((history.note ?? history.image) == null)
            ? null
            : const EdgeInsets.only(right: 12, left: 12, bottom: 12),
        trailing: ((history.note ?? history.image) == null)
            ? const SizedBox(width: 0)
            : null,
        collapsedShape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
        ),
        onExpansionChanged: (value) {},
        collapsedBackgroundColor: context.color.accentContrastColor,
        title: ExpansionTitle(
            priceAmount: history.amount,
            feeAmount: history.fee,
            paymentGateway: history.gatewayName.toString(),
            paymentStatus: history.status.toString(),
            id: history.id,
            controller: controller,
            paymentFailed: false),
        children: ((history.note ?? history.image) == null)
            ? []
            : [
                ExpansionChildren(history.note, history.image, path),
              ],
      ),
    );
  }
}
