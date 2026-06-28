import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/date_schedule_service.dart';

class SelectSlotSheet extends StatefulWidget {
  final day;
  final DateScheduleService ds;
  const SelectSlotSheet({super.key, this.day, required this.ds});

  @override
  State<SelectSlotSheet> createState() => _SelectSlotSheetState();
}

class _SelectSlotSheetState extends State<SelectSlotSheet> {
  @override
  Widget build(BuildContext context) {
    final dsm = AddEditServiceViewModel.instance;
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Align(
            alignment: Alignment.center,
            child: Container(
              height: 4,
              width: 48,
              margin: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12),
                color: context.color.mutedContrastColor,
              ),
            ),
          ),
          FieldLabel(label: LocalKeys.selectSlot),
          CheckboxListTile(
            value: widget.ds.selectedSchedules[widget.day].length == 9,
            contentPadding: EdgeInsets.zero,
            dense: true,
            onChanged: (v) {
              if (widget.ds.selectedSchedules[widget.day].isNotEmpty) {
                widget.ds.removeAllSlots(widget.day);
              } else {
                List.generate(9, (e) {
                  widget.ds.addRemoveSlot(e, widget.day);
                });
              }
              setState(() {});
            },
            title: Text(
              LocalKeys.selectAll,
              style: context.titleSmall,
            ),
            controlAffinity: ListTileControlAffinity.leading,
          ),
          12.toHeight,
          24.toHeight,
          CustomButton(
              onPressed: () {
                context.pop;
              },
              btText: LocalKeys.done)
        ],
      ),
    );
  }
}
