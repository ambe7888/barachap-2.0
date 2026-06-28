import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';

class BookingSummeryButtons extends StatelessWidget {
  const BookingSummeryButtons({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: OutlinedButton(
                onPressed: () {}, child: Text(LocalKeys.addToCart)),
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: ElevatedButton(
                onPressed: () {}, child: Text(LocalKeys.proceedToPay)),
          ),
        ],
      ),
    );
  }
}
