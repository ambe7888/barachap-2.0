import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/staff_view_model/staff_view_model.dart';

class AddStaffButton extends StatelessWidget {
  const AddStaffButton({super.key});

  @override
  Widget build(BuildContext context) {
    final sm = StaffViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ValueListenableBuilder(
        valueListenable: sm.isLoading,
        builder: (context, value, child) => CustomButton(
          onPressed: () {
            sm.tryAddEditStaff(context);
          },
          btText: sm.staffId != null ? LocalKeys.editStaff : LocalKeys.addStaff,
          isLoading: value,
        ),
      ),
    );
  }
}
