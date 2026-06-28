import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../../view_models/date_schedule_view_model/date_schedule_view_model.dart';
import 'add_edit_schedule_sheet.dart';

class SelectScheduleButton extends StatelessWidget {
  const SelectScheduleButton({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final dsm = DateScheduleViewModel.instance;
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: () {
          dsm.endTime.value = null;
          dsm.startTime.value = null;
          dsm.scheduleId = null;
          showModalBottomSheet(
            context: context,
            backgroundColor: context.color.cardFillColor,
            isScrollControlled: true,
            builder: (context) {
              return const AddEditScheduleSheet(
                editing: false,
              );
            },
          );
        },
        child: Text(LocalKeys.addSlot),
      ),
    );
  }
}
