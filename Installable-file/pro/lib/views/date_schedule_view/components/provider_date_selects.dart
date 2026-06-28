import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';

import '../../../helper/constant_helper.dart';
import '../../../services/date_schedule_service.dart';
import '../../../view_models/date_schedule_view_model/date_schedule_view_model.dart';

class ProviderDateSelects extends StatelessWidget {
  final DateScheduleService ds;
  const ProviderDateSelects({super.key, required this.ds});

  @override
  Widget build(BuildContext context) {
    final dsm = DateScheduleViewModel.instance;
    List<int> weekdays = [
      DateTime.monday,
      DateTime.tuesday,
      DateTime.wednesday,
      DateTime.thursday,
      DateTime.friday,
      DateTime.saturday,
      DateTime.sunday,
    ];
    return Wrap(
      runSpacing: 12,
      children: weekdays.map((i) {
        final isSelected = ds.selectedSchedules.containsKey(i);
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CheckboxListTile(
              value: isSelected,
              onChanged: (v) {
                ds.setDay(i);
              },
              title: Text(
                DateFormat("EEEE", dProvider.languageSlug)
                    .format(DateTime.now().copyWith(day: i)),
                style: context.titleSmall?.bold,
              ),
              controlAffinity: ListTileControlAffinity.leading,
              contentPadding: EdgeInsets.zero,
            ).hp20,
            if (isSelected) ...[],
          ],
        );
      }).toList(),
    );
  }
}
