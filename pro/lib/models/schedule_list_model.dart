import 'dart:convert';

ScheduleListModel scheduleListModelFromJson(String str) =>
    ScheduleListModel.fromJson(json.decode(str));

String scheduleListModelToJson(ScheduleListModel data) =>
    json.encode(data.toJson());

class ScheduleListModel {
  final List<Schedule>? schedules;

  ScheduleListModel({
    this.schedules,
  });

  factory ScheduleListModel.fromJson(json) => ScheduleListModel(
        schedules: json["schedules"] == null
            ? []
            : List<Schedule>.from(
                json["schedules"]!.map((x) => Schedule.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "schedules": schedules == null
            ? []
            : List<dynamic>.from(schedules!.map((x) => x.toJson())),
      };
}

class Schedule {
  final dynamic id;
  final dynamic providerId;
  final String? day;
  final String? schedule;
  final dynamic status;
  final dynamic allowMultipleSchedule;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Schedule({
    this.id,
    this.providerId,
    this.day,
    this.schedule,
    this.status,
    this.allowMultipleSchedule,
    this.createdAt,
    this.updatedAt,
  });

  factory Schedule.fromJson(Map<String, dynamic> json) => Schedule(
        id: json["id"],
        providerId: json["provider_id"],
        day: json["day"],
        schedule: json["schedule"],
        status: json["status"],
        allowMultipleSchedule: json["allow_multiple_schedule"],
        createdAt: json["created_at"] == null
            ? null
            : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null
            ? null
            : DateTime.parse(json["updated_at"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "provider_id": providerId,
        "status": status,
        "allow_multiple_schedule": allowMultipleSchedule,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
      };
}
