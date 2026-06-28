import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/schedule_list_model.dart';
import '../../view_models/date_schedule_view_model/date_schedule_view_model.dart';

class ScheduleListService with ChangeNotifier {
  ScheduleListModel? _scheduleListModel;
  ScheduleListModel get scheduleListModel =>
      _scheduleListModel ?? ScheduleListModel();

  String token = "";
  var day;

  bool get shouldAutoFetch {
    final dsm = DateScheduleViewModel.instance;
    return day != dsm.selectedDay.value.weekday || token.isInvalid;
  }

  fetchScheduleList() async {
    final dsm = DateScheduleViewModel.instance;
    token = getToken;
    day = dsm.selectedDay.value.weekday;
    final weekdayName = DateFormat('EEEE').format(dsm.selectedDay.value);
    var url = "${AppUrls.scheduleListUrl}$weekdayName";

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.schedule, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      final tempData = ScheduleListModel.fromJson(responseData);

      _scheduleListModel = tempData;
    } else {
      _scheduleListModel ??= ScheduleListModel();
    }
    notifyListeners();
  }

  void removeSchedule(id) {
    _scheduleListModel?.schedules
        ?.removeWhere((schedule) => schedule.id.toString() == id.toString());
    notifyListeners();
  }
}
