import 'dart:convert';

IdentityVerificationInfoModel identityVerificationInfoModelFromJson(
        String str) =>
    IdentityVerificationInfoModel.fromJson(json.decode(str));

String identityVerificationInfoModelToJson(
        IdentityVerificationInfoModel data) =>
    json.encode(data.toJson());

class IdentityVerificationInfoModel {
  final UserVerifyInfo? userVerifyInfo;

  IdentityVerificationInfoModel({
    this.userVerifyInfo,
  });

  factory IdentityVerificationInfoModel.fromJson(Map json) =>
      IdentityVerificationInfoModel(
        userVerifyInfo: json["user_verify_info"] == null
            ? null
            : UserVerifyInfo.fromJson(json["user_verify_info"]),
      );

  Map<String, dynamic> toJson() => {
        "user_verify_info": userVerifyInfo?.toJson(),
      };
}

class UserVerifyInfo {
  final dynamic id;
  final dynamic userId;
  final String? identificationType;
  final String? identificationNumber;
  final String? frontDocument;
  final String? backDocument;
  final String? country;
  final String? state;
  final String? city;
  final String? zipCode;
  final String? address;
  final String? status;

  UserVerifyInfo({
    this.id,
    this.userId,
    this.identificationType,
    this.identificationNumber,
    this.frontDocument,
    this.backDocument,
    this.country,
    this.state,
    this.city,
    this.zipCode,
    this.address,
    this.status,
  });

  factory UserVerifyInfo.fromJson(Map<String, dynamic> json) => UserVerifyInfo(
        id: json["id"],
        userId: json["user_id"],
        identificationType: json["identification_type"],
        identificationNumber: json["identification_number"],
        frontDocument: json["front_document"],
        backDocument: json["back_document"],
        country: json["country"],
        state: json["state"],
        city: json["city"],
        zipCode: json["zip_code"],
        address: json["address"],
        status: json["status"]?.toString(),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "user_id": userId,
        "identification_type": identificationType,
        "identification_number": identificationNumber,
        "front_document": frontDocument,
        "back_document": backDocument,
        "country": country,
        "state": state,
        "city": city,
        "zip_code": zipCode,
        "address": address,
        "status": status,
      };
}
