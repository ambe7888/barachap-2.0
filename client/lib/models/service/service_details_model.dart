import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/profile_models/review_list_model.dart';

import '../address_models/address_model.dart';
import '../category_model.dart';
import '../home_models/services_list_model.dart';
import '../provider_model.dart';

ServiceDetailsModel serviceDetailsModelFromJson(String str) =>
    ServiceDetailsModel.fromJson(json.decode(str));

String serviceDetailsModelToJson(ServiceDetailsModel data) =>
    json.encode(data.toJson());

class ServiceDetailsModel {
  ServiceDetails? allServices;
  final List<ServiceModel>? relatedServices;

  ServiceDetailsModel({
    this.allServices,
    this.relatedServices,
  });

  factory ServiceDetailsModel.fromJson(json) => ServiceDetailsModel(
        allServices: json["service_details"] == null
            ? null
            : ServiceDetails.fromJson(json["service_details"]),
        relatedServices: json["relevant_service_lists"] == null
            ? []
            : List<ServiceModel>.from(json["relevant_service_lists"]!
                .map((x) => ServiceModel.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "service_details": allServices?.toJson(),
      };
}

class ServiceDetails {
  final dynamic id;
  final dynamic categoryId;
  final Category? category;
  final dynamic subCategoryId;
  final dynamic childCategoryId;
  final String? title;
  final String? slug;
  final String? unit;
  final String? videoId;
  final num price;
  final num? discountPrice;
  final String? description;
  final dynamic isFeatured;
  final String? image;
  num view;
  num soldCount;
  num totalReviews;
  num averageRating;
  final num avgRating;
  final List<String>? galleryImages;
  final List<AdditionalInfo>? offers;
  final List<AdditionalInfo>? excludes;
  final List<AdditionalInfo>? faqs;
  final List<Addon>? addons;
  final ProviderModel? provider;
  final AdminModel? admin;
  final List<ReviewModel>? reviews;

  ServiceDetails({
    this.id,
    this.categoryId,
    this.category,
    this.subCategoryId,
    this.childCategoryId,
    this.title,
    this.slug,
    this.videoId,
    this.unit,
    required this.price,
    this.discountPrice,
    this.description,
    this.isFeatured,
    this.image,
    this.avgRating = 0,
    required this.view,
    required this.soldCount,
    required this.totalReviews,
    required this.averageRating,
    this.galleryImages,
    this.offers,
    this.excludes,
    this.faqs,
    this.addons,
    this.provider,
    this.admin,
    this.reviews,
  });

  factory ServiceDetails.fromJson(Map<String, dynamic> json) => ServiceDetails(
        id: json["id"],
        categoryId: json["category_id"],
        category: json["category"] == null
            ? null
            : Category.fromJson(json["category"]),
        subCategoryId: json["sub_category_id"],
        childCategoryId: json["child_category_id"],
        title: json["title"],
        slug: json["slug"],
        unit: json["unit"],
        videoId: json["video_url"],
        price: json["price"].toString().tryToParse,
        discountPrice: json["discount_price"]?.toString().tryToParse,
        description: json["description"],
        avgRating: json["average_rating"].toString().tryToParse,
        view: json["view"].toString().tryToParse,
        soldCount: json["sold_count"].toString().tryToParse,
        totalReviews: json["total_reviews"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        isFeatured: json["is_featured"],
        image: json["image"],
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
        reviews: (json["reviews"] ?? json["reviews_all"]) == null
            ? []
            : List<ReviewModel>.from((json["reviews"] ?? json["reviews_all"])!
                .map((x) => ReviewModel.fromJson(x))),
        provider: json["provider"] == null
            ? null
            : ProviderModel.fromJson(json["provider"]),
        admin:
            json["admin"] == null ? null : AdminModel.fromJson(json["admin"]),
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
  Map<String, dynamic> toMinimizedJson() => {
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
      };
}

class Addon {
  dynamic id;
  dynamic serviceId;
  String? title;
  num price;
  num quantity;
  dynamic image;
  String? description;

  Addon({
    this.id,
    this.serviceId,
    this.title,
    required this.price,
    required this.quantity,
    this.image,
    this.description,
  });

  factory Addon.fromJson(Map<String, dynamic> json) => Addon(
        id: json["id"],
        serviceId: json["service_id"],
        title: json["title"],
        price: json["price"].toString().tryToParse,
        quantity: json["quantity"].toString().tryToParse,
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

class AdminModel {
  final dynamic id;
  final String? name;
  final String? email;
  final dynamic image;
  final List<Staff> staffs;
  final Address? serviceArea;

  AdminModel({
    this.id,
    this.name,
    this.email,
    this.image,
    required this.staffs,
    this.serviceArea,
  });

  factory AdminModel.fromJson(Map<String, dynamic> json) => AdminModel(
        id: json["id"],
        name: json["name"],
        email: json["email"],
        image: json["image"],
        staffs: (json["staffs"]?["all_staffs"]) == null
            ? []
            : List<Staff>.from(
                json["staffs"]!["all_staffs"]!.map((x) => Staff.fromJson(x))),
        serviceArea: json["service_location"] == null
            ? null
            : Address.fromJson(json["service_location"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "email": email,
        "image": image,
      };
}

class Staff {
  final dynamic id;
  final dynamic providerId;
  final dynamic adminId;
  final String? fullname;
  final String? firstName;
  final String? lastName;
  final String? email;
  final String? phone;
  final String? about;
  final dynamic status;
  final dynamic image;

  Staff({
    this.id,
    this.providerId,
    this.adminId,
    this.fullname,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.about,
    this.status,
    this.image,
  });

  factory Staff.fromJson(Map<String, dynamic> json) => Staff(
        id: json["id"],
        providerId: json["provider_id"],
        adminId: json["admin_id"],
        fullname: json["fullname"],
        firstName: json["first_name"],
        lastName: json["last_name"],
        email: json["email"],
        phone: json["phone"],
        about: json["about"],
        status: json["status"],
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "provider_id": providerId,
        "admin_id": adminId,
        "fullname": fullname,
        "first_name": firstName,
        "last_name": lastName,
        "email": email,
        "phone": phone,
        "about": about,
        "status": status,
        "image": image,
      };
  Map<String, dynamic> toMinimizedJson() => {
        "id": id,
        "fullname": fullname,
        "first_name": firstName,
        "last_name": lastName,
        "image": image,
      };
}
