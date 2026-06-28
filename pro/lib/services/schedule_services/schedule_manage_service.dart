import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../view_models/date_schedule_view_model/date_schedule_view_model.dart';

class ScheduleManageService {
  tryAddingSchedule() async {
    var url = AppUrls.addScheduleUrl;
    final dsm = DateScheduleViewModel.instance;
    final weekdayName = DateFormat('EEEE').format(dsm.selectedDay.value);
    final data = {
      'day': weekdayName,
      'schedule':
          "${DateFormat("hh:mm a", dProvider.languageSlug).format(dsm.startTime.value!)} - ${DateFormat("hh:mm a", dProvider.languageSlug).format(dsm.endTime.value!)}",
      'schedule_for_all_days': dsm.allDays.value ? "1" : '0'
    };
    final responseData = await NetworkApiServices()
        .postApi(data, url, LocalKeys.addSlot, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.scheduleAddedSuccessfully.showToast();
      return true;
    }
  }

  tryEditingSchedule() async {
    var url = AppUrls.editScheduleUrl;
    final dsm = DateScheduleViewModel.instance;
    final data = {
      'id': dsm.scheduleId.toString(),
      'day': dsm.weekdayName.toString(),
      'schedule':
          "${DateFormat("hh:mm a", dProvider.languageSlug).format(dsm.startTime.value!)} - ${DateFormat("hh:mm a", dProvider.languageSlug).format(dsm.endTime.value!)}",
      'schedule_for_all_days': '0'
    };

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.editSchedule,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.scheduleEditedSuccessfully.showToast();
      return true;
    }
  }

  tryRemovingSchedule(id) async {
    var url = "${AppUrls.deleteScheduleUrl}$id";
    final data = {};

    final responseData = await NetworkApiServices().postApi(
        data, url, LocalKeys.removeSchedule,
        headers: acceptJsonAuthHeader);

    if (responseData != null) {
      LocalKeys.scheduleRemovedSuccessfully.showToast();
      return true;
    }
  }
}
