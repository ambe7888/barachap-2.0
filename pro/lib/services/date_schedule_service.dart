import 'package:flutter/material.dart';

class DateScheduleService with ChangeNotifier {
  final Map _selectedSchedules = {};
  Map get selectedSchedules => _selectedSchedules;

  setDay(day) {
    if (selectedSchedules.containsKey(day)) {
      _selectedSchedules.remove(day);
    } else {
      _selectedSchedules.putIfAbsent(day, () => []);
    }
    debugPrint(selectedSchedules.toString());
    notifyListeners();
  }

  void addRemoveSlot(e, day) {
    debugPrint(selectedSchedules.toString());
    if (selectedSchedules[day].contains(e)) {
      _selectedSchedules[day].remove(e);
    } else {
      _selectedSchedules[day].add(e);
    }
    notifyListeners();
  }

  void setAllSchedule() {}

  void removeAllSlots(day) {
    selectedSchedules[day].clear();
    notifyListeners();
  }
}
