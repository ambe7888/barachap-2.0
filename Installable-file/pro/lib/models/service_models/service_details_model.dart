import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/profile_models/review_list_model.dart';
import 'package:prohand/models/provider_model.dart';

import '../category_model.dart';

ServiceDetailsModel serviceDetailsModelFromJson(String str) =>
    ServiceDetailsModel.fromJson(json.decode(str));

String serviceDetailsModelToJson(ServiceDetailsModel data) =>
    json.encode(data.toJson());

class ServiceDetailsModel {
  AllServices? allServices;

  ServiceDetailsModel({
    this.allServices,
  });

  factory ServiceDetailsModel.fromJson(json) => ServiceDetailsModel(
        allServices: json["service_details"] == null
            ? null
            : AllServices.fromJson(json["service_details"]),
      );

  Map<String, dynamic> toJson() => {
        "service_details": allServices?.toJson(),
      };
}

class AllServices {
  dynamic id;
  dynamic categoryId;
  Category? category;
  dynamic subCategoryId;
  dynamic childCategoryId;
  String? title;
  String? slug;
  String? unit;
  String? videoUrl;
  num price;
  bool isPublished;
  num? discountPrice;
  num view;
  num soldCount;
  num totalReviews;
  num averageRating;
  String? description;
  dynamic isFeatured;
  String? image;
  List<String>? galleryImages;
  List<AdditionalInfo>? offers;
  List<AdditionalInfo>? excludes;
  List<AdditionalInfo>? faqs;
  final bool allocatedStaffOnly;
  List<Addon>? addons;
  final List<ReviewModel>? reviews;
  final ProviderModel? provider;

  AllServices({
    this.id,
    this.categoryId,
    this.category,
    this.subCategoryId,
    this.childCategoryId,
    this.title,
    this.slug,
    this.videoUrl,
    this.unit,
    required this.price,
    required this.isPublished,
    this.discountPrice,
    this.view = 0,
    this.soldCount = 0,
    this.allocatedStaffOnly = false,
    this.totalReviews = 0,
    this.averageRating = 0,
    this.description,
    this.isFeatured,
    this.image,
    this.galleryImages,
    this.offers,
    this.excludes,
    this.faqs,
    this.addons,
    this.reviews,
    this.provider,
  });

  factory AllServices.fromJson(Map<String, dynamic> json) => AllServices(
        id: json["id"],
        categoryId: json["category_id"],
        category: Category.fromJson(json["category"]),
        subCategoryId: json["sub_category_id"],
        childCategoryId: json["child_category_id"],
        title: json["title"],
        slug: json["slug"],
        unit: json["unit"],
        videoUrl: json["video_url"],
        price: json["price"].toString().tryToParse,
        isPublished: json["is_published"].toString().parseToBool,
        allocatedStaffOnly: json["is_published"].toString().parseToBool,
        discountPrice: json["discount_price"]?.toString().tryToParse,
        description: json["description"],
        isFeatured: json["is_featured"],
        image: json["image"],
        view: json["view"].toString().tryToParse,
        soldCount: json["sold_count"].toString().tryToParse,
        totalReviews: json["total_reviews"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        galleryImages: json["gallery_images"] == null
            ? []
            : List<String>.from(json["gallery_images"]!.map((x) => x)),
        offers: json["includes"] == null
            ? []
            : List<AdditionalInfo>.from(
                json["includes"]!.map((x) => AdditionalInfo.fromJson(x))),
        excludes: json["excludes"] == null
            ? []
            : List<AdditionalInfo>.from(
                json["excludes"]!.map((x) => AdditionalInfo.fromJson(x))),
        faqs: json["faqs"] == null
            ? []
            : List<AdditionalInfo>.from(
                json["faqs"]!.map((x) => AdditionalInfo.fromJson(x))),
        addons: json["addons"] == null
            ? []
            : List<Addon>.from(json["addons"]!.map((x) => Addon.fromJson(x))),
        reviews: json["reviews"] == null
            ? []
            : List<ReviewModel>.from(
                json["reviews"]!.map((x) => ReviewModel.fromJson(x))),
        provider: json["provider"] == null
            ? null
            : ProviderModel.fromJson(json["provider"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "category_id": categoryId,
        "sub_category_id": subCategoryId,
        "child_category_id": childCategoryId,
        "title": title,
        "slug": slug,
        "unit": unit,
        "price": price,
        "discount_price": discountPrice,
        "description": description,
        "is_featured": isFeatured,
        "image": image,
        "gallery_images": galleryImages == null
            ? []
            : List<dynamic>.from(galleryImages!.map((x) => x)),
        "addons": addons == null
            ? []
            : List<dynamic>.from(addons!.map((x) => x.toJson())),
      };
}

class Addon {
  dynamic id;
  dynamic serviceId;
  String? title;
  num price;
  dynamic image;
  String? description;

  Addon({
    this.id,
    this.serviceId,
    this.title,
    required this.price,
    this.image,
    this.description,
  });

  factory Addon.fromJson(Map<String, dynamic> json) => Addon(
        id: json["id"],
        serviceId: json["service_id"],
        title: json["title"],
        price: json["price"].toString().tryToParse,
        image: json["image"],
        description: json["description"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "addon_service_title": title ?? "",
        "addon_service_price": price.toString(),
        "addon_service_description": description.toString(),
        "image": image,
        "addon_service_image": image,
      };
}

class AdditionalInfo {
  dynamic id;
  dynamic serviceId;
  String? title;
  String? description;

  AdditionalInfo({
    this.id,
    this.serviceId,
    this.title,
    this.description,
  });

  factory AdditionalInfo.fromJson(Map<String, dynamic> json) => AdditionalInfo(
        id: json["id"],
        serviceId: json["service_id"],
        title: json["title"],
        description: json["description"],
      );

  dynamic toFaq() => {
        "id": id,
        "service_id": serviceId,
        "faq_service_title": title,
        "faq_service_description": description,
      };
  dynamic toInclude() => {
        "id": id,
        "service_id": serviceId,
        "include_service_title": title,
        "include_service_description": description,
      };
  dynamic toExclude() => {
        "id": id,
        "service_id": serviceId,
        "exclude_service_title": title,
        "exclude_service_description": description,
      };
}
