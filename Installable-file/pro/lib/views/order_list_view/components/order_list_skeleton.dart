import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/order_list_view/components/order_list_tile_skeleton.dart';

class OrderListSkeleton extends StatelessWidget {
  const OrderListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 12),
      child: Wrap(
        runSpacing: 16,
        children: List.generate(
          10,
          (index) => const OrderListTileSkeleton(),
        ),
      ).shim,
    );
  }
}
