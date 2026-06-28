import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';

import '../service_models/service_list_model.dart';

ReviewListModel reviewListModelFromJson(String str) =>
    ReviewListModel.fromJson(json.decode(str));

String reviewListModelToJson(ReviewListModel data) =>
    json.encode(data.toJson());

class ReviewListModel {
  final dynamic providerRating;
  final List<ReviewModel> reviews;
  final Pagination? pagination;

  ReviewListModel({
    this.providerRating,
    required this.reviews,
    this.pagination,
  });

  factory ReviewListModel.fromJson(Map json) => ReviewListModel(
        providerRating: json["provider_rating"],
        reviews: json["provider_all_reviews"] == null
            ? []
            : List<ReviewModel>.from(json["provider_all_reviews"]!
                .map((x) => ReviewModel.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "provider_rating": providerRating,
        "provider_all_reviews":
            List<dynamic>.from(reviews.map((x) => x.toJson())),
      };
}

class ReviewModel {
  final dynamic id;
  final dynamic userId;
  final num rating;
  final String? type;
  final String? message;
  final String? status;
  final String? service;
  final Reviewer? reviewer;
  final DateTime? createdAt;

  ReviewModel({
    this.id,
    this.userId,
    required this.rating,
    this.type,
    this.message,
    this.service,
    this.status,
    this.reviewer,
    this.createdAt,
  });

  factory ReviewModel.fromJson(Map<String, dynamic> json) => ReviewModel(
        id: json["id"],
        userId: json["user_id"],
        rating: json["rating"].toString().tryToParse,
        type: json["type"],
        message: json["message"],
        service: json["service"],
        status: json["status"],
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        reviewer: (json["reviewer"] ?? json["provider_reviews"]) == null
            ? null
            : Reviewer.fromJson((json["reviewer"] ?? json["provider_reviews"])),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "user_id": userId,
        "rating": rating,
        "type": type,
        "message": message,
        "status": status,
        "reviewer": reviewer?.toJson(),
      };
}

class Reviewer {
  final dynamic id;
  final String? firstName;
  final String? name;
  final String? image;

  Reviewer({
    this.id,
    this.firstName,
    this.image,
    this.name,
  });

  factory Reviewer.fromJson(Map<String, dynamic> json) => Reviewer(
        id: json["id"],
        firstName: json["first_name"],
        image: json["image"],
        name: json["fullname"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "first_name": firstName,
        "image": image,
      };
}
