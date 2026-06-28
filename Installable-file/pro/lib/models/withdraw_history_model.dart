import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/service_models/service_list_model.dart';

WithdrawHistoryModel withdrawHistoryModelFromJson(String str) =>
    WithdrawHistoryModel.fromJson(json.decode(str));

String withdrawHistoryModelToJson(WithdrawHistoryModel data) =>
    json.encode(data.toJson());

class WithdrawHistoryModel {
  List<History>? histories;
  Pagination? pagination;

  WithdrawHistoryModel({this.histories, this.pagination});

  factory WithdrawHistoryModel.fromJson(Map json) => WithdrawHistoryModel(
        histories: json["histories"] == null
            ? []
            : List<History>.from(
                json["histories"]!.map((x) => History.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {};
}

class Histories {
  List<History>? history;
  dynamic nextPageUrl;

  Histories({
    this.history,
    this.nextPageUrl,
  });

  factory Histories.fromJson(Map<String, dynamic> json) => Histories(
        history: json["data"] == null
            ? []
            : List<History>.from(json["data"]!.map((x) => History.fromJson(x))),
        nextPageUrl: json["next_page_url"],
      );

  Map<String, dynamic> toJson() => {
        "history": history == null
            ? []
            : List<dynamic>.from(history!.map((x) => x.toJson())),
        "next_page_url": nextPageUrl,
      };
}

class History {
  dynamic id;
  num amount;
  num fee;
  dynamic gatewayId;
  dynamic gatewayName;
  dynamic userId;
  dynamic status;
  dynamic gatewayFields;
  String? note;
  String? image;
  DateTime? createdAt;
  DateTime? updatedAt;

  History({
    this.id,
    required this.amount,
    required this.fee,
    this.gatewayId,
    this.userId,
    this.status,
    this.gatewayName,
    this.gatewayFields,
    this.note,
    this.image,
    this.createdAt,
    this.updatedAt,
  });

  factory History.fromJson(Map<String, dynamic> json) {
    var history = History(
      id: json["id"],
      amount: json["amount"].toString().tryToParse,
      fee: json["fee"].toString().tryToParse,
      gatewayId: json["gateway_id"],
      gatewayName: json["gateway_name"],
      userId: json["user_id"],
      status: json["status"],
      note: json["note"],
      image: json["image"],
      createdAt: json["created_at"] == null
          ? null
          : DateTime.parse(json["created_at"]),
      updatedAt: json["updated_at"] == null
          ? null
          : DateTime.parse(json["updated_at"]),
    );
    try {
      if (json["field"] is Map) {
        history.gatewayFields = json["field"];
      } else {
        history.gatewayFields = {};
      }
    } catch (e) {
      history.gatewayFields = {};
    }
    return history;
  }

  Map<String, dynamic> toJson() => {
        "id": id,
        "amount": amount,
        "gateway_id": gatewayId,
        "user_id": userId,
        "status": status,
        "gateway_fields": gatewayFields,
        "note": note,
        "image": image,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
      };
}
