import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/models/provider_model.dart';

JobDetailsModel scheduleListModelFromJson(String str) =>
    JobDetailsModel.fromJson(json.decode(str));

String scheduleListModelToJson(JobDetailsModel data) =>
    json.encode(data.toJson());

class JobDetailsModel {
  final JobDetails? jobDetails;
  final dynamic isJobHired;

  JobDetailsModel({
    this.jobDetails,
    this.isJobHired,
  });

  factory JobDetailsModel.fromJson(Map json) => JobDetailsModel(
        jobDetails: json["job_details"] == null
            ? null
            : JobDetails.fromJson(json["job_details"]),
        isJobHired: json["is_job_hired"]?.toString(),
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
  final dynamic isFeatured;
  final String? status;
  String? isPublished;
  final DateTime? date;
  final String? time;
  final List<String> galleryImages;
  final Address? jobLocation;
  final List<JobOffer> jobOffers;

  JobDetails({
    this.id,
    this.clientId,
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
    this.isPublished,
    this.date,
    this.time,
    required this.galleryImages,
    this.jobLocation,
    required this.jobOffers,
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
        isFeatured: json["is_featured"],
        status: json["status"]?.toString(),
        isPublished: json["is_published"]?.toString(),
        date: DateTime.tryParse(json["date"].toString()),
        time: json["time"],
        galleryImages: json["gallery_images"] == null
            ? []
            : List.from(json["gallery_images"]!.map((x) => x ?? "")),
        jobLocation: json["job_location"] == null
            ? null
            : Address.fromJson(json["job_location"]),
        jobOffers: json["job_offers"] == null
            ? []
            : List<JobOffer>.from(
                json["job_offers"]!.map((x) => JobOffer.fromJson(x))),
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
        "is_published": isPublished,
        "date":
            "${date!.year.toString().padLeft(4, '0')}-${date!.month.toString().padLeft(2, '0')}-${date!.day.toString().padLeft(2, '0')}",
        "time": time,
        "gallery_images": List<dynamic>.from(galleryImages.map((x) => x)),
        "job_location": jobLocation,
        "job_offers": List<dynamic>.from(jobOffers.map((x) => x.toJson())),
      };
}

class JobOffer {
  final dynamic id;
  final dynamic jobPostId;
  final dynamic clientId;
  final dynamic providerId;
  final num budget;
  final String? coverLetter;
  final bool isHired;
  final ProviderModel? provider;

  JobOffer({
    this.id,
    this.jobPostId,
    this.clientId,
    this.providerId,
    this.budget = 0,
    this.coverLetter,
    this.isHired = false,
    this.provider,
  });

  factory JobOffer.fromJson(Map<String, dynamic> json) => JobOffer(
        id: json["id"],
        jobPostId: json["job_post_id"],
        clientId: json["client_id"],
        providerId: json["provider_id"],
        budget: json["budget"].toString().tryToParse,
        coverLetter: json["cover_letter"],
        isHired: json["is_hired"].toString().parseToBool,
        provider: json["provider"] == null
            ? null
            : ProviderModel.fromJson(json["provider"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "job_post_id": jobPostId,
        "client_id": clientId,
        "provider_id": providerId,
        "budget": budget,
        "cover_letter": coverLetter,
        "is_hired": isHired,
        "provider": provider?.toJson(),
      };
}
