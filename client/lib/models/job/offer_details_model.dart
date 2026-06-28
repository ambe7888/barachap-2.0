import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../address_models/address_model.dart';
import '../provider_model.dart';

OfferDetailsModel offerDetailsModelFromJson(String str) =>
    OfferDetailsModel.fromJson(json.decode(str));

String offerDetailsModelToJson(OfferDetailsModel data) =>
    json.encode(data.toJson());

class OfferDetailsModel {
  final Message? message;

  OfferDetailsModel({
    this.message,
  });

  factory OfferDetailsModel.fromJson(Map json) => OfferDetailsModel(
        message:
            json["message"] == null ? null : Message.fromJson(json["message"]),
      );

  Map<String, dynamic> toJson() => {
        "message": message?.toJson(),
      };
}

class Message {
  final dynamic id;
  final dynamic jobPostId;
  dynamic clientId;
  dynamic status;
  final dynamic providerId;
  final num budget;
  final String? coverLetter;
  String isHired;
  String isRejected;
  final ProviderModel? provider;
  final Address? jobLocation;
  final DateTime? createdAt;

  Message({
    this.id,
    this.jobPostId,
    this.clientId,
    this.providerId,
    required this.budget,
    this.coverLetter,
    required this.isHired,
    required this.status,
    required this.isRejected,
    this.provider,
    this.jobLocation,
    this.createdAt,
  });

  factory Message.fromJson(Map<String, dynamic> json) => Message(
      id: json["id"],
      jobPostId: json["job_post_id"],
      clientId: json["client_id"],
      status: json["status"],
      providerId: json["provider_id"],
      budget: json["budget"].toString().tryToParse,
      coverLetter: json["cover_letter"],
      isHired: json["is_hired"].toString(),
      isRejected: json["is_rejected"].toString(),
      provider: json["provider"] == null
          ? null
          : ProviderModel.fromJson(json["provider"]),
      jobLocation: json["job_location"] == null
          ? null
          : Address.fromJson(json["job_location"]),
      createdAt: DateTime.tryParse(json["created_at"].toString()));

  Map<String, dynamic> toJson() => {
        "id": id,
        "job_post_id": jobPostId,
        "client_id": status,
        "provider_id": providerId,
        "budget": budget,
        "cover_letter": coverLetter,
        "is_hired": isHired,
        "provider": provider?.toJson(),
        "job_location": jobLocation?.toJson(),
      };
}
