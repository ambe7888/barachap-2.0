import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../address_models/states_model.dart';
import '../provider_model.dart';

OfferListModel offerListModelFromJson(String str) =>
    OfferListModel.fromJson(json.decode(str));

String offerListModelToJson(OfferListModel data) => json.encode(data.toJson());

class OfferListModel {
  final List<JobOffer> jobOffers;
  final Pagination? pagination;

  OfferListModel({
    required this.jobOffers,
    this.pagination,
  });

  factory OfferListModel.fromJson(Map json) => OfferListModel(
        jobOffers: json["job_offers"] == null
            ? []
            : List<JobOffer>.from(
                json["job_offers"]!.map((x) => JobOffer.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "job_offers": jobOffers == null
            ? []
            : List<dynamic>.from(jobOffers.map((x) => x.toJson())),
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
  final String? status;
  final DateTime? createdAt;
  final Job? job;
  final ProviderModel? provider;

  JobOffer({
    this.id,
    this.jobPostId,
    this.clientId,
    this.providerId,
    this.budget = 0,
    this.coverLetter,
    this.isHired = false,
    this.status,
    this.createdAt,
    this.job,
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
        status: json["status"]?.toString(),
        createdAt: json["created_at"] == null
            ? null
            : DateTime.parse(json["created_at"]),
        job: json["job"] == null ? null : Job.fromJson(json["job"]),
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
        "status": status,
        "created_at": createdAt?.toIso8601String(),
        "job": job?.toJson(),
        "provider": provider?.toJson(),
      };
}

class Job {
  final dynamic id;
  final String? title;
  final String? slug;
  final num budget;
  final DateTime? createdAt;

  Job({
    this.id,
    this.title,
    this.slug,
    this.budget = 0,
    this.createdAt,
  });

  factory Job.fromJson(Map<String, dynamic> json) => Job(
        id: json["id"],
        title: json["title"],
        slug: json["slug"],
        budget: json["budget"].toString().tryToParse,
        createdAt: json["created_at"] == null
            ? null
            : DateTime.parse(json["created_at"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "title": title,
        "slug": slug,
        "budget": budget,
        "created_at": createdAt?.toIso8601String(),
      };
}
