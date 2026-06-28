import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';

JobDetailsModel jobDetailsModelFromJson(String str) =>
    JobDetailsModel.fromJson(json.decode(str));

String jobDetailsModelToJson(JobDetailsModel data) =>
    json.encode(data.toJson());

class JobDetailsModel {
  final JobDetails? jobDetails;
  final dynamic isJobHired;
  final JobOffer? jobOffer;

  JobDetailsModel({
    this.jobDetails,
    this.isJobHired,
    this.jobOffer,
  });

  factory JobDetailsModel.fromJson(json) => JobDetailsModel(
        jobDetails: json["job_details"] == null
            ? null
            : JobDetails.fromJson(json["job_details"]),
        isJobHired: json["is_job_hired"],
        jobOffer: json["job_offers"] == null
            ? null
            : JobOffer.fromJson(json["job_offers"]),
      );

  Map<String, dynamic> toJson() => {
        "job_details": jobDetails?.toJson(),
        "is_job_hired": isJobHired,
      };
}

class JobDetails {
  final dynamic id;
  final dynamic clientId;
  final dynamic categoryId;
  final dynamic subCategoryId;
  final dynamic childCategoryId;
  final String? title;
  final String? slug;
  final num budget;
  final num view;
  final String? description;
  final String? category;
  final dynamic isFeatured;
  final dynamic status;
  final DateTime? date;
  final DateTime? createdAt;
  final String? time;
  final List galleryImages;
  final Address? jobLocation;
  final Client? client;
  final JobOffer? jobOffers;

  JobDetails({
    this.id,
    this.clientId,
    this.category,
    this.categoryId,
    this.subCategoryId,
    this.childCategoryId,
    this.title,
    this.slug,
    required this.budget,
    required this.view,
    this.description,
    this.isFeatured,
    this.status,
    this.date,
    this.createdAt,
    this.time,
    required this.galleryImages,
    this.jobLocation,
    this.client,
    this.jobOffers,
  });

  factory JobDetails.fromJson(Map<String, dynamic> json) => JobDetails(
        id: json["id"],
        clientId: json["client_id"],
        categoryId: json["category_id"],
        subCategoryId: json["sub_category_id"],
        childCategoryId: json["child_category_id"],
        title: json["title"],
        slug: json["slug"],
        budget: json["budget"].toString().tryToParse,
        view: json["view"].toString().tryToParse,
        description: json["description"],
        category: json["category"],
        isFeatured: json["is_featured"],
        status: json["status"],
        date: DateTime.tryParse(json["date"].toString()),
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        time: json["time"],
        galleryImages: json["gallery_images"] == null
            ? []
            : List.from(json["gallery_images"]!.map((x) => x)),
        jobLocation: json["job_location"] == null
            ? null
            : Address.fromJson(json["job_location"]),
        client: json["client"] == null ? null : Client.fromJson(json["client"]),
        jobOffers: json["job_offers"]?.isEmpty ?? true
            ? null
            : JobOffer.fromJson(json["job_offers"].first),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "client_id": clientId,
        "category_id": categoryId,
        "sub_category_id": subCategoryId,
        "child_category_id": childCategoryId,
        "title": title,
        "slug": slug,
        "budget": budget,
        "view": view,
        "description": description,
        "is_featured": isFeatured,
        "status": status,
        "date":
            "${date!.year.toString().padLeft(4, '0')}-${date!.month.toString().padLeft(2, '0')}-${date!.day.toString().padLeft(2, '0')}",
        "time": time,
        "job_location": jobLocation,
      };
}

class Client {
  final dynamic id;
  final String? fullname;
  final String? image;
  final num reviewCount;
  final num averageRating;
  final DateTime? createdAt;
  final DateTime? clientLastSeen;
  final num clientTotalJobs;

  Client({
    this.id,
    this.fullname,
    this.image,
    required this.reviewCount,
    required this.averageRating,
    this.createdAt,
    this.clientLastSeen,
    required this.clientTotalJobs,
  });

  factory Client.fromJson(Map<String, dynamic> json) => Client(
        id: json["id"],
        fullname: json["fullname"],
        image: json["image"],
        reviewCount: json["review_count"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        clientLastSeen: DateTime.tryParse(json["client_last_seen"].toString()),
        clientTotalJobs: json["client_total_jobs"].toString().tryToParse,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "first_name": fullname,
        "image": image,
        "review_count": reviewCount,
        "average_rating": averageRating,
        "created_at": createdAt?.toIso8601String(),
        "client_last_seen": clientLastSeen?.toIso8601String(),
        "client_total_jobs": clientTotalJobs,
      };
}

class JobOffer {
  final dynamic id;
  final Client? provider;
  final num budget;
  final String? coverLetter;
  final dynamic isHired;
  final dynamic status;

  JobOffer({
    this.id,
    this.provider,
    required this.budget,
    this.coverLetter,
    this.isHired,
    this.status,
  });

  factory JobOffer.fromJson(json) => JobOffer(
        id: json["id"],
        provider:
            json["provider"] == null ? null : Client.fromJson(json["provider"]),
        budget: json["budget"].toString().tryToParse,
        coverLetter: json["cover_letter"],
        isHired: json["is_hired"],
        status: json["status"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "provider": provider?.toJson(),
        "budget": budget,
        "cover_letter": coverLetter,
        "is_hired": isHired,
      };
}
