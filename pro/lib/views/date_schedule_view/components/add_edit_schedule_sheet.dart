import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/view_models/date_schedule_view_model/date_schedule_view_model.dart';

class AddEditScheduleSheet extends StatelessWidget {
  final bool editing;
  const AddEditScheduleSheet({super.key, required this.editing});

  @override
  Widget build(BuildContext context) {
    final dsm = DateScheduleViewModel.instance;
    final now = DateTime.now();
    return SingleChildScrollView(
      padding: EdgeInsets.only(
        left: 24,
        right: 24,
        bottom: MediaQuery.of(context).viewInsets.bottom,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(
            height: 16,
          ),
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
          12.toHeight,
          FieldLabel(
            label: LocalKeys.selectTime,
            isRequired: true,
          ),
          8.toHeight,
          Row(
            children: [
              Expanded(
                flex: 1,
                child: GestureDetector(
                  onTap: () {
                    showTimePicker(
                      context: context,
                      initialTime: dsm.startTime.value == null
                          ? TimeOfDay.now()
                          : TimeOfDay(
                              hour: dsm.startTime.value!.hour,
                              minute: dsm.startTime.value!.minute),
                      builder: (BuildContext context, Widget? child) {
                        return Localizations.override(
                          context: context,
                          locale: dProvider
                              .appLocal, // Change 'fr' to your desired locale code
                          child: child,
                        );
                      },
                    ).then((value) {
                      if (value == null) return;
                      dsm.startTime.value = DateTime(now.year, now.month,
                          now.day, value.hour, value.minute);
                    });
                  },
                  child: SquircleContainer(
                      height: 40,
                      radius: 10,
                      borderColor: context.color.primaryBorderColor,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 6, vertical: 4),
                      child: Center(
                        child: ValueListenableBuilder(
                          valueListenable: dsm.startTime,
                          builder: (context, value, child) {
                            return Text(
                              value == null
                                  ? LocalKeys.startTime
                                  : DateFormat(
                                          "hh:mm a", dProvider.languageSlug)
                                      .format(value),
                              style: context.titleSmall,
                            );
                          },
                        ),
                      )),
                ),
              ),
              12.toWidth,
              Expanded(
                flex: 1,
                child: GestureDetector(
                  onTap: () {
                    showTimePicker(
                      context: context,
                      initialTime: dsm.endTime.value == null
                          ? TimeOfDay.now()
                          : TimeOfDay(
                              hour: dsm.endTime.value!.hour,
                              minute: dsm.endTime.value!.minute),
                      builder: (BuildContext context, Widget? child) {
                        return Localizations.override(
                          context: context,
                          locale: dProvider
                              .appLocal, // Change 'fr' to your desired locale code
                          child: child,
                        );
                      },
                    ).then((value) {
                      if (value == null) return;
                      dsm.endTime.value = DateTime(now.year, now.month, now.day,
                          value.hour, value.minute);
                    });
                  },
                  child: SquircleContainer(
                      height: 40,
                      radius: 10,
                      borderColor: context.color.primaryBorderColor,
                      padding: const EdgeInsets.symmetric(
                          horizontal: 6, vertical: 4),
                      child: Center(
                        child: ValueListenableBuilder(
                            valueListenable: dsm.endTime,
                            builder: (context, value, child) {
                              return Text(
                                value == null
                                    ? LocalKeys.endTime
                                    : DateFormat(
                                            "hh:mm a", dProvider.languageSlug)
                                        .format(value),
                                style: context.titleSmall,
                              );
                            }),
                      )),
                ),
              ),
            ],
          ),
          if (!editing) ...[
            16.toHeight,
            ValueListenableBuilder(
              valueListenable: dsm.allDays,
              builder: (context, allDays, child) => SwitchListTile(
                value: allDays,
                contentPadding: EdgeInsets.zero,
                onChanged: (value) {
                  dsm.allDays.value = !allDays;
                },
                title: Text(
                  LocalKeys.addToAllDays,
                  style: context.titleSmall?.bold,
                ),
                subtitle: Text(
                  LocalKeys.scheduleSlotHint,
                  style: context.bodySmall,
                ),
              ),
            )
          ],
          16.toHeight,
          Row(
            children: [
              Expanded(
                  flex: 1,
                  child: OutlinedButton(
                      onPressed: () {
                        context.pop;
                      },
                      child: Text(LocalKeys.cancel))),
              12.toWidth,
              Expanded(
                  flex: 1,
                  child: ValueListenableBuilder(
                    valueListenable: dsm.isLoading,
                    builder: (context, loading, child) => CustomButton(
                      onPressed: () {
                        dsm.tryAddingSchedule(context);
                      },
                      btText: editing ? LocalKeys.saveChanges : LocalKeys.add,
                      isLoading: loading,
                    ),
                  )),
            ],
          ),
          24.toHeight,
        ],
      ),
    );
  }
}
