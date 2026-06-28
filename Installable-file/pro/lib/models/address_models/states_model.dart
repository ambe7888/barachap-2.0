import 'dart:convert';

import '../service_models/service_list_model.dart';

StatesModel? countryModelFromJson(String str) =>
    StatesModel.fromJson(json.decode(str));

String countryModelToJson(StatesModel? data) => json.encode(data!.toJson());

class StatesModel {
  StatesModel({
    required this.states,
    this.pagination,
  });

  Pagination? pagination;
  List<States?> states;

  factory StatesModel.fromJson(json) => StatesModel(
        states: json["states"] == null
            ? []
            : List<States?>.from(
                json["states"]!.map((x) => States.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "countries": states == null
            ? []
            : List<dynamic>.from(states.map((x) => x!.toJson())),
      };
}

class States {
  final dynamic id;
  final String? state;
  final dynamic stateCode;
  final dynamic stateStateCode;
  final dynamic latitude;
  final dynamic longitude;
  final dynamic status;

  States({
    this.id,
    this.state,
    this.stateCode,
    this.stateStateCode,
    this.latitude,
    this.longitude,
    this.status,
  });

  factory States.fromJson(Map<String, dynamic> json) => States(
        id: json["id"],
        state: json["state"],
        stateCode: json["state_code"],
        stateStateCode: json["state_code "],
        latitude: json["latitude"],
        longitude: json["longitude"],
        status: json["status"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "state": state,
        "state_code": stateCode,
        "state_code ": stateStateCode,
        "latitude": latitude,
        "longitude": longitude,
        "status": status,
      };
}
