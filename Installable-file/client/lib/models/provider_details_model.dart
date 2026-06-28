import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/models/home_models/services_list_model.dart';
import 'package:prohandy_client/models/profile_models/review_list_model.dart';
import 'package:prohandy_client/models/service/service_details_model.dart';

import 'category_model.dart';

ProviderDetailsModel providerDetailsModelFromJson(String str) =>
    ProviderDetailsModel.fromJson(json.decode(str));

String providerDetailsModelToJson(ProviderDetailsModel data) =>
    json.encode(data.toJson());

class ProviderDetailsModel {
  final UserDetails? userDetails;

  ProviderDetailsModel({
    this.userDetails,
  });

  factory ProviderDetailsModel.fromJson(Map json) => ProviderDetailsModel(
        userDetails: json["user_details"] == null
            ? null
            : UserDetails.fromJson(json["user_details"]),
      );

  Map<String, dynamic> toJson() => {
        "user_details": userDetails?.toJson(),
      };
}

class UserDetails {
  final dynamic id;
  final String? fullName;
  final String? image;
  final String? videoUrl;
  final num totalServiceOrderCompleted;
  final num totalJobOrderCompleted;
  final num reviewCount;
  final num averageRating;
  final num orderCompletionRate;
  final num customerSatisfactionRate;
  final bool verifiedStatus;
  final DateTime? lastSeen;
  final DateTime? createdAt;
  final List<Category>? serviceCategories;
  final Address? providerServiceArea;
  final List<ServiceModel>? services;
  final List<ReviewModel>? reviews;
  final List<Staff>? providerStaffs;
  final List? storeImage;

  UserDetails({
    this.id,
    this.fullName,
    this.image,
    this.videoUrl,
    required this.totalServiceOrderCompleted,
    required this.totalJobOrderCompleted,
    required this.reviewCount,
    required this.averageRating,
    required this.orderCompletionRate,
    required this.customerSatisfactionRate,
    required this.verifiedStatus,
    this.lastSeen,
    this.createdAt,
    this.serviceCategories,
    this.providerServiceArea,
    this.services,
    this.reviews,
    this.providerStaffs,
    this.storeImage,
  });

  factory UserDetails.fromJson(Map<String, dynamic> json) => UserDetails(
        id: json["id"],
        fullName: json["full_name"],
        storeImage: json["store_images"],
        videoUrl: json["video_url"],
        image: json["image"],
        totalServiceOrderCompleted:
            json["total_service_order_completed"].toString().tryToParse,
        totalJobOrderCompleted:
            json["total_job_order_completed"].toString().tryToParse,
        reviewCount: json["review_count"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        orderCompletionRate:
            json["order_completion_rate"].toString().tryToParse,
        customerSatisfactionRate:
            json["customer_satisfaction_rate"].toString().tryToParse,
        verifiedStatus: json["verified_status"].toString().parseToBool,
        lastSeen: DateTime.tryParse(json["last_seen"].toString()),
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        serviceCategories: json["service_categories"] == null
            ? []
            : List<Category>.from(
                json["service_categories"]!.map((x) => Category.fromJson(x))),
        providerServiceArea: json["provider_service_area"] == null
            ? null
            : Address.fromJson(json["provider_service_area"]),
        services: json["services"] == null
            ? []
            : List<ServiceModel>.from(
                json["services"]!.map((x) => ServiceModel.fromJson(x))),
        reviews: json["reviews"] == null
            ? []
            : List<ReviewModel>.from(
                json["reviews"]!.map((x) => ReviewModel.fromJson(x))),
        providerStaffs: json["provider_staffs"] == null
            ? []
            : List<Staff>.from(
                json["provider_staffs"]!.map((x) => Staff.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "full_name": fullName,
        "image": image,
        "total_job_order_completed": totalJobOrderCompleted,
        "review_count": reviewCount,
        "average_rating": averageRating,
        "order_completion_rate": orderCompletionRate,
        "customer_satisfaction_rate": customerSatisfactionRate,
        "verified_status": verifiedStatus,
        "last_seen": lastSeen?.toIso8601String(),
        "created_at": createdAt?.toIso8601String(),
        "service_categories": serviceCategories == null
            ? []
            : List<dynamic>.from(serviceCategories!.map((x) => x.toJson())),
        "provider_service_area": providerServiceArea?.toJson(),
        "services": services == null
            ? []
            : List<dynamic>.from(services!.map((x) => x.toJson())),
        "reviews": reviews == null
            ? []
            : List<dynamic>.from(reviews!.map((x) => x.toJson())),
        "provider_staffs": providerStaffs == null
            ? []
            : List<dynamic>.from(providerStaffs!.map((x) => x.toJson())),
      };
}
