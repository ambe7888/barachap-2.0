import 'package:flutter/material.dart';

class ServiceBookingViewModel {
  final ValueNotifier<DateTime?> selectedDate = ValueNotifier(null);
  final ValueNotifier<String?> selectedSchedule = ValueNotifier("12:00-18:00");

  ValueNotifier dateScheduleType = ValueNotifier(SelectingScheduleType.date);

  ServiceBookingViewModel._init();
  static ServiceBookingViewModel? _instance;
  static ServiceBookingViewModel get instance {
    _instance ??= ServiceBookingViewModel._init();
    return _instance!;
  }

  ServiceBookingViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}

enum SelectingScheduleType { date, schedule }
