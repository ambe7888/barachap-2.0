import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/schedule_list_model.dart';
import 'package:prohand/services/schedule_services/schedule_list_service.dart';
import 'package:prohand/services/schedule_services/schedule_manage_service.dart';
import 'package:provider/provider.dart';

import '../../utils/components/alerts.dart';

class DateScheduleViewModel {
  final ValueNotifier<DateTime?> startTime = ValueNotifier(null);
  final ValueNotifier<DateTime?> endTime = ValueNotifier(null);

  final ValueNotifier<DateTime> selectedDay = ValueNotifier(DateTime.now());
  final ValueNotifier<bool> allDays = ValueNotifier(false);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  dynamic scheduleId;
  dynamic weekdayName;

  DateScheduleViewModel._init();
  static DateScheduleViewModel? _instance;
  static DateScheduleViewModel get instance {
    _instance ??= DateScheduleViewModel._init();
    return _instance!;
  }

  DateScheduleViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryAddingSchedule(BuildContext context) async {
    if (startTime.value == null) {
      LocalKeys.selectStartTime.showToast();
      return;
    }
    if (endTime.value == null) {
      LocalKeys.selectEndTime.showToast();
      return;
    }
    isLoading.value = true;
    if (scheduleId == null) {
      await ScheduleManageService().tryAddingSchedule().then((v) {
        if (v == true) {
          Provider.of<ScheduleListService>(context, listen: false)
              .fetchScheduleList();
          context.pop;
        }
      });
    } else {
      await ScheduleManageService().tryEditingSchedule().then((v) {
        if (v == true) {
          Provider.of<ScheduleListService>(context, listen: false)
              .fetchScheduleList();
          context.pop;
        }
      });
    }

    isLoading.value = false;
  }

  void initSchedule(Schedule schedule) {
    scheduleId = schedule.id;
    weekdayName = schedule.day ?? "";
    DateTime today = DateTime.now();
    final fSchedule = schedule.schedule?.split("-").firstOrNull?.trim();
    final eSchedule = schedule.schedule?.split("-").lastOrNull?.trim();
    startTime.value = fSchedule != null
        ? DateFormat('yyyy-MM-dd h:mm a')
            .tryParse("${DateFormat('yyyy-MM-dd').format(today)} $fSchedule")
        : null;
    endTime.value = fSchedule != null
        ? DateFormat('yyyy-MM-dd h:mm a')
            .tryParse("${DateFormat('yyyy-MM-dd').format(today)} $eSchedule")
        : null;
  }

  void tryRemovingStaff(BuildContext context, {id}) {
    Alerts().confirmationAlert(
      context: context,
      title: LocalKeys.areYouSure,
      buttonText: LocalKeys.remove,
      onConfirm: () async {
        await ScheduleManageService().tryRemovingSchedule(id).then((v) {
          if (v == true) {
            Provider.of<ScheduleListService>(context, listen: false)
                .removeSchedule(id);
            context.pop;
          }
        });
      },
    );
  }
}
