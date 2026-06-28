import 'package:flutter/material.dart';
import 'package:prohand/app_static_values.dart';
import 'package:prohand/utils/components/selectable_button.dart';

import '../../../view_models/identity_verify_view_model/identity_verify_view_model.dart';

class IDTypeButtons extends StatelessWidget {
  const IDTypeButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final ivm = IVViewModel.instance;
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Wrap(
        spacing: 8,
        children: idVTypes.keys.toList().map((ty) {
          return ValueListenableBuilder(
            valueListenable: ivm.selectedIVType,
            builder: (context, value, child) => SelectableButton(
              title: idVTypes[ty] ?? '',
              notifier: ivm.selectedIVType,
              isSelected: ivm.selectedIVType.value == ty,
              onPressed: () {
                ivm.selectedIVType.value = ty;
              },
            ),
          );
        }).toList(),
      ),
    );
  }
}
