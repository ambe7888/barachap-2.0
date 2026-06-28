import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';

import 'category_model.dart';
import 'service_models/service_list_model.dart' as sl;
import 'staff_models/staff_list_model.dart';

class ProviderListModel {
  final List<ProviderModel>? providerLists;
  final sl.Pagination? pagination;

  ProviderListModel({
    this.providerLists,
    this.pagination,
  });

  factory ProviderListModel.fromJson(Map json) => ProviderListModel(
        providerLists: json["provider_lists"] == null
            ? []
            : List<ProviderModel>.from(
                json["provider_lists"]!.map((x) => ProviderModel.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : sl.Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "provider_lists": providerLists == null
            ? []
            : List<dynamic>.from(providerLists!.map((x) => x.toJson())),
      };
}

class ProviderModel {
  final dynamic id;
  final String name;
  final String? image;
  final num avgRating;
  final String? profession;
  final num? ratingCount;
  final num? completionRate;
  final num? customerSatisfactionRate;
  final bool isVerified;
  final num orderCompleted;
  final num jobCompleted;
  final Address? address;
  final DateTime? createdAt;
  final DateTime? lastSeen;
  final dynamic firstName;
  final dynamic lastName;
  final String? email;
  final dynamic phone;

  final List<Staff>? staffs;
  final List<Category>? serviceCategories;
  final Address? providerServiceArea;
  ProviderModel({
    required this.name,
    this.image,
    required this.avgRating,
    required this.id,
    this.profession,
    this.ratingCount,
    this.completionRate,
    this.isVerified = false,
    this.orderCompleted = 0,
    this.customerSatisfactionRate,
    this.lastSeen,
    this.serviceCategories,
    this.providerServiceArea,
    this.jobCompleted = 0,
    this.address,
    this.createdAt,
    this.firstName,
    this.lastName,
    this.email,
    this.phone,
    this.staffs,
  });

  factory ProviderModel.fromJson(Map<String, dynamic> json) => ProviderModel(
        id: json["id"],
        name: json["full_name"],
        image: json["image"],
        firstName: json["first_name"],
        lastName: json["last_name"],
        email: json["email "],
        phone: json["phone"],
        staffs: json["staffs"] == null
            ? []
            : List<Staff>.from(json["staffs"]!.map((x) => Staff.fromJson(x))),
        ratingCount: json["review_count"].toString().tryToParse,
        avgRating: json["average_rating"].toString().tryToParse,
        completionRate: json["order_completion_rate"].toString().tryToParse,
        customerSatisfactionRate:
            json["customer_satisfaction_rate"].toString().tryToParse,
        isVerified: json["verified_status"].toString().parseToBool,
        lastSeen: DateTime.tryParse(json["last_seen"].toString()),
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        serviceCategories: json["service_categories"] == null
            ? []
            : List<Category>.from(
                json["service_categories"]!.map((x) => Category.fromJson(x))),
        providerServiceArea: json["provider_service_area"] == null
            ? null
            : Address.fromJson(json["provider_service_area"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "full_name": name,
        "image": image,
        "review_count": ratingCount,
        "average_rating": avgRating,
        "order_completion_rate": completionRate,
        "customer_satisfaction_rate": customerSatisfactionRate,
        "verified_status": isVerified,
        "last_seen": lastSeen?.toIso8601String(),
        "created_at": createdAt?.toIso8601String(),
        "service_categories": serviceCategories == null
            ? []
            : List<dynamic>.from(serviceCategories!.map((x) => x.toJson())),
        "provider_service_area": providerServiceArea?.toJson(),
      };
}
