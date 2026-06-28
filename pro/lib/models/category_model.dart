import 'dart:convert';

import 'package:prohand/models/service_models/service_list_model.dart';

CategoryListModel orderDetailsModelFromJson(String str) =>
    CategoryListModel.fromJson(json.decode(str));

String orderDetailsModelToJson(CategoryListModel data) =>
    json.encode(data.toJson());

class CategoryListModel {
  final List<Category> categories;
  final Pagination? pagination;

  CategoryListModel({
    required this.categories,
    this.pagination,
  });

  factory CategoryListModel.fromJson(Map json) => CategoryListModel(
      categories: json["categories"] == null
          ? []
          : List<Category>.from(
              json["categories"]!.map((x) => Category.fromJson(x))),
      pagination: json["pagination"] == null
          ? null
          : Pagination.fromJson(json["pagination"]));

  Map<String, dynamic> toJson() => {
        "categories": List<dynamic>.from(categories.map((x) => x.toJson())),
      };
}

class Category {
  dynamic id;
  String? name;
  String? slug;
  dynamic description;
  String? image;

  Category({
    this.id,
    this.name,
    this.slug,
    this.description,
    this.image,
  });

  factory Category.fromJson(Map<String, dynamic> json) => Category(
        id: json["id"],
        name: json["name"],
        slug: json["slug"],
        description: json["description"],
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "slug": slug,
        "description": description,
        "image": image,
      };
}
