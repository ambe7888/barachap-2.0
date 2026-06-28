import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';

ServiceListModel serviceListModelFromJson(String str) =>
    ServiceListModel.fromJson(json.decode(str));

String serviceListModelToJson(ServiceListModel data) =>
    json.encode(data.toJson());

class ServiceListModel {
  List<AllService>? allServices;
  Pagination? pagination;

  ServiceListModel({
    this.allServices,
    this.pagination,
  });

  factory ServiceListModel.fromJson(Map json) => ServiceListModel(
        allServices: json["all_services"] == null
            ? []
            : List<AllService>.from(
                json["all_services"]!.map((x) => AllService.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "all_services": allServices == null
            ? []
            : List<dynamic>.from(allServices!.map((x) => x.toJson())),
        "pagination": pagination?.toJson(),
      };
}

class AllService {
  dynamic id;
  Category? category;
  dynamic subCategory;
  dynamic childCategory;
  String? title;
  String? slug;
  String? unit;
  num price;
  num discountPrice;
  num view;
  num soldCount;
  num totalReviews;
  num averageRating;
  dynamic isFeatured;
  String? image;
  dynamic admin;

  AllService({
    this.id,
    this.category,
    this.subCategory,
    this.childCategory,
    this.title,
    this.slug,
    this.unit,
    required this.price,
    required this.discountPrice,
    required this.view,
    required this.soldCount,
    required this.totalReviews,
    required this.averageRating,
    this.isFeatured,
    this.image,
    this.admin,
  });

  factory AllService.fromJson(Map<String, dynamic> json) => AllService(
        id: json["id"],
        category: json["category"] == null
            ? null
            : Category.fromJson(json["category"]),
        subCategory: json["sub_category"],
        childCategory: json["child_category"],
        title: json["title"],
        slug: json["slug"],
        unit: json["unit"],
        price: json["price"].toString().tryToParse,
        discountPrice: json["discount_price"].toString().tryToParse,
        isFeatured: json["is_featured"],
        image: json["image"],
        view: json["view"].toString().tryToParse,
        soldCount: json["sold_count"].toString().tryToParse,
        totalReviews: json["total_reviews"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        admin: json["admin"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "category": category?.toJson(),
        "sub_category": subCategory,
        "child_category": childCategory,
        "title": title,
        "slug": slug,
        "unit": unit,
        "price": price,
        "discount_price": discountPrice,
        "is_featured": isFeatured,
        "image": image,
        "admin": admin,
      };
}

class Category {
  dynamic id;
  String? name;
  String? slug;
  String? icon;
  dynamic description;
  String? image;

  Category({
    this.id,
    this.name,
    this.slug,
    this.icon,
    this.description,
    this.image,
  });

  factory Category.fromJson(Map<String, dynamic> json) => Category(
        id: json["id"],
        name: json["name"],
        slug: json["slug"],
        icon: json["icon"],
        description: json["description"],
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "slug": slug,
        "icon": icon,
        "description": description,
        "image": image,
      };
}

class Pagination {
  String? nextPageUrl;
  dynamic prevPageUrl;

  Pagination({
    this.nextPageUrl,
    this.prevPageUrl,
  });

  factory Pagination.fromJson(Map<String, dynamic> json) => Pagination(
        nextPageUrl: json["next_page_url"],
        prevPageUrl: json["prev_page_url"],
      );

  Map<String, dynamic> toJson() => {
        "next_page_url": nextPageUrl,
        "prev_page_url": prevPageUrl,
      };
}
