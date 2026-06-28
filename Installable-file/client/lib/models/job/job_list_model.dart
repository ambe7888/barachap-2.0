import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../address_models/states_model.dart';

JobListModel scheduleListModelFromJson(String str) =>
    JobListModel.fromJson(json.decode(str));

String scheduleListModelToJson(JobListModel data) => json.encode(data.toJson());

class JobListModel {
  final List<Job>? jobs;
  final Pagination? pagination;

  JobListModel({
    this.jobs,
    this.pagination,
  });

  factory JobListModel.fromJson(json) => JobListModel(
        jobs: json["jobs"] == null || json["jobs"] is! List
            ? []
            : List<Job>.from(json["jobs"]!.map((x) => Job.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "jobs": jobs == null
            ? []
            : List<dynamic>.from(jobs!.map((x) => x.toJson())),
      };
}

class Job {
  final dynamic id;
  final String? title;
  final String? slug;
  final num budget;
  final dynamic view;
  final String? description;
  final String status;
  final num jobOffersCount;
  final List<JobOffer>? jobOffers;

  Job({
    this.id,
    this.title,
    this.slug,
    this.budget = 0,
    this.view,
    this.description,
    this.status = "",
    this.jobOffersCount = 0,
    this.jobOffers,
  });

  factory Job.fromJson(Map<String, dynamic> json) => Job(
        id: json["id"],
        title: json["title"],
        slug: json["slug"],
        budget: json["budget"].toString().tryToParse,
        view: json["view"].toString().tryToParse,
        description: json["description"],
        status: json["status"].toString(),
        jobOffersCount: json["job_offers_count"].toString().tryToParse,
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "title": title,
        "slug": slug,
        "budget": budget,
        "view": view,
        "description": description,
        "status": status,
        "job_offers_count": jobOffersCount,
        "job_offers": jobOffers == null
            ? []
            : List<dynamic>.from(jobOffers!.map((x) => x.toJson())),
      };
}

class JobOffer {
  final dynamic id;
  final dynamic jobId;
  final dynamic clientId;
  final dynamic providerId;
  final dynamic budget;
  final String? coverLetter;
  final dynamic isHired;

  JobOffer({
    this.id,
    this.jobId,
    this.clientId,
    this.providerId,
    this.budget,
    this.coverLetter,
    this.isHired,
  });

  factory JobOffer.fromJson(Map<String, dynamic> json) => JobOffer(
        id: json["id"],
        jobId: json["job_id"],
        clientId: json["client_id"],
        providerId: json["provider_id"],
        budget: json["budget"],
        coverLetter: json["cover_letter"],
        isHired: json["is_hired"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "job_id": jobId,
        "client_id": clientId,
        "provider_id": providerId,
        "budget": budget,
        "cover_letter": coverLetter,
        "is_hired": isHired,
      };
}
