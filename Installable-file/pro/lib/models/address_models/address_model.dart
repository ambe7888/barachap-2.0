import 'package:prohand/helper/extension/string_extension.dart';

class Address {
  final dynamic id;
  final dynamic stateId;
  final dynamic cityId;
  final dynamic areaId;
  final dynamic phone;
  final dynamic postCode;
  final String? title;
  final String? stateName;
  final String? cityName;
  final String? areaName;
  final String? address;
  final double? latitude;
  final double? longitude;

  Address({
    this.id,
    this.stateId,
    this.cityId,
    this.areaId,
    this.stateName,
    this.cityName,
    this.areaName,
    this.phone,
    this.postCode,
    this.title,
    this.address,
    this.latitude,
    this.longitude,
  });

  factory Address.fromJson(Map<String, dynamic> json) => Address(
        id: json["id"],
        stateId: json["state_id"],
        cityId: json["city_id"],
        areaId: json["area_id"],
        stateName: json["state_name"],
        cityName: json["city_name"],
        areaName: json["area_name"],
        phone: json["phone"],
        postCode: json["post_code"],
        address: json["address"],
        latitude: json["latitude"]?.toString().tryToParse.toDouble(),
        longitude: json["longitude"]?.toString().tryToParse.toDouble(),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "state_id": stateId,
        "city_id": cityId,
        "area_id": areaId,
        "phone": phone,
        "post_code": postCode,
        "address": address,
        "latitude": latitude,
        "longitude": longitude,
      };
}
