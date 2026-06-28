import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import 'selectable_staff_tile.dart';

class OrderAcceptStaffs extends StatelessWidget {
  const OrderAcceptStaffs({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.preferredStaffs,
            style: context.titleMedium?.bold
                .copyWith(color: context.color.tertiaryContrastColo),
          ),
          12.toHeight,
          Wrap(
            spacing: 12,
            children: List.generate(3, (staff) {
              return SelectableStaffTile(
                id: staff,
                name: "John Wick",
                onSelect: () {},
                valueListenable: ValueNotifier(null),
              );
            }),
          )
        ],
      ),
    );
  }
}
