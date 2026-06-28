import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import 'expansion_children.dart';
import 'expansion_title.dart';

class WithdrawHistoryTile extends StatelessWidget {
  final dynamic id;
  final num amount;
  final num feeAmount;
  final String pStatus;
  final String pMethod;
  final String? image;
  final String? note;
  final DateTime? cDate;
  final ExpansionTileController? controller;
  final String? path;
  const WithdrawHistoryTile({
    super.key,
    this.id,
    required this.amount,
    required this.feeAmount,
    required this.pStatus,
    required this.pMethod,
    this.note,
    this.image,
    this.cDate,
    this.controller,
    this.path,
  });

  @override
  Widget build(BuildContext context) {
    return ExpansionTile(
      backgroundColor: context.color.accentContrastColor,
      tilePadding: EdgeInsets.only(
          right: 20,
          left: 20,
          top: 20,
          bottom: ((note ?? image) == null) ? 20 : 0),
      childrenPadding: ((note ?? image) == null)
          ? null
          : const EdgeInsets.only(right: 20, left: 20, bottom: 20),
      trailing: ((note ?? image) == null) ? const SizedBox(width: 0) : null,
      collapsedShape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
      ),
      onExpansionChanged: (value) {},
      collapsedBackgroundColor: context.color.accentContrastColor,
      title: ExpansionTitle(
          priceAmount: amount,
          feeAmount: feeAmount,
          paymentGateway: pMethod,
          paymentStatus: pStatus,
          id: id,
          controller: controller,
          paymentFailed: false),
      children: ((note ?? image) == null)
          ? []
          : [
              ExpansionChildren(note, image, path),
            ],
    );
  }
}
