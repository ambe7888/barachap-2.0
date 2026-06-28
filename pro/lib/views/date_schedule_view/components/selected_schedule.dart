import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../models/schedule_list_model.dart';
import '../../../view_models/date_schedule_view_model/date_schedule_view_model.dart';
import 'add_edit_schedule_sheet.dart';

class SelectedSchedule extends StatelessWidget {
  final String slot;
  final void Function()? onSlotChanged;
  final bool isSelected;
  final Schedule schedule;
  const SelectedSchedule({
    super.key,
    required this.slot,
    this.onSlotChanged,
    this.isSelected = false,
    required this.schedule,
  });

  @override
  Widget build(BuildContext context) {
    final dsm = DateScheduleViewModel.instance;
    return GestureDetector(
      onTapDown: (details) {
        Alerts.showPopupMenu(context, details, {
          "edit": LocalKeys.edit,
          "remove": LocalKeys.remove,
        }, (value) {
          switch (value) {
            case "edit":
              dsm.initSchedule(schedule);
              showModalBottomSheet(
                context: context,
                backgroundColor: context.color.cardFillColor,
                isScrollControlled: true,
                builder: (context) {
                  return const AddEditScheduleSheet(
                    editing: true,
                  );
                },
              );
              break;
            default:
              dsm.tryRemovingStaff(context, id: schedule.id);
          }
        });
      },
      child: SquircleContainer(
          borderColor: context.color.primaryBorderColor,
          radius: 10,
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
          child: FittedBox(
            child: Row(
              children: [
                Container(
                  child: Text(
                    slot,
                    style: context.titleSmall?.bold,
                  ),
                ),
                2.toWidth,
                Icon(
                  Icons.more_vert_rounded,
                  size: 20,
                  color: context.color.tertiaryContrastColo,
                )
              ],
            ),
          )),
    );
  }
}
