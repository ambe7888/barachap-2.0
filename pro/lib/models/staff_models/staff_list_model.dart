import 'dart:convert';

import '../service_models/service_list_model.dart';

StaffListModel staffListModelFromJson(String str) =>
    StaffListModel.fromJson(json.decode(str));

String staffListModelToJson(StaffListModel data) => json.encode(data.toJson());

class StaffListModel {
  final List<Staff>? allStaffs;
  final Pagination? pagination;

  StaffListModel({
    this.allStaffs,
    this.pagination,
  });

  factory StaffListModel.fromJson(json) => StaffListModel(
        allStaffs: json["all_staffs"] == null
            ? []
            : List<Staff>.from(
                json["all_staffs"]!.map((x) => Staff.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "all_staffs": allStaffs == null
            ? []
            : List<dynamic>.from(allStaffs!.map((x) => x.toJson())),
        "pagination": pagination?.toJson(),
      };
}

class Staff {
  final dynamic id;
  final String? fullname;
  final String? firstName;
  final String? lastName;
  final String? email;
  final String? phone;
  final String? about;
  final dynamic status;
  final String? image;

  Staff({
    this.id,
    this.fullname,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.about,
    this.status,
    this.image,
  });

  factory Staff.fromJson(Map<String, dynamic> json) => Staff(
        id: json["id"],
        fullname: json["fullname"],
        firstName: json["first_name"],
        lastName: json["last_name"],
        email: json["email "],
        phone: json["phone"],
        about: json["about"],
        status: json["status"],
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "fullname": fullname,
        "first_name": firstName,
        "last_name": lastName,
        "email ": email,
        "phone": phone,
        "about": about,
        "status": status,
        "image": image,
      };
}
