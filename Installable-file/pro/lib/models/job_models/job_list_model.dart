import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';

import '../address_models/address_model.dart';
import '../service_models/service_list_model.dart';

JobListModel jobDetailsModelFromJson(String str) =>
    JobListModel.fromJson(json.decode(str));

String jobDetailsModelToJson(JobListModel data) => json.encode(data.toJson());

class JobListModel {
  final List<Job> jobs;
  final Pagination? pagination;

  JobListModel({
    required this.jobs,
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
        "pagination": pagination?.toJson(),
      };
}

class Job {
  final dynamic id;
  final String? category;
  final String? subCategory;
  final String? childCategory;
  final String? title;
  final String? slug;
  final num budget;
  final Address? jobLocation;
  final String? latLng;
  final DateTime? createdAt;

  Job({
    this.id,
    this.category,
    this.subCategory,
    this.childCategory,
    this.title,
    this.slug,
    required this.budget,
    this.jobLocation,
    this.latLng,
    this.createdAt,
  });

  factory Job.fromJson(Map<String, dynamic> json) => Job(
        id: json["id"],
        category: json["category"],
        subCategory: json["sub_category"],
        childCategory: json["child_category"],
        title: json["title"],
        slug: json["slug"],
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        budget: json["budget"].toString().tryToParse,
        latLng:
            "${json["job_location"]?["latitude"]}${json["job_location"]?["longitude"]}",
        jobLocation: json["job_location"] == null
            ? null
            : Address.fromJson(json["job_location"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "category": category,
        "title": title,
        "slug": slug,
        "budget": budget,
        "job_location": jobLocation?.toJson(),
      };
}
