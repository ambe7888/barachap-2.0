import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';
import 'package:prohand/models/job_models/job_list_model.dart';

import '../category_model.dart';
import '../job_models/job_details_model.dart';
import '../staff_models/staff_list_model.dart';
import 'complete_request_history_list_model.dart';

OrderDetailsModel orderDetailsModelFromJson(String str) =>
    OrderDetailsModel.fromJson(json.decode(str));

String orderDetailsModelToJson(OrderDetailsModel data) =>
    json.encode(data.toJson());

class OrderDetailsModel {
  final OrderDetails? orderDetails;

  OrderDetailsModel({
    this.orderDetails,
  });

  factory OrderDetailsModel.fromJson(Map json) => OrderDetailsModel(
        orderDetails: json["order_details"] == null
            ? null
            : OrderDetails.fromJson(json["order_details"]),
      );

  Map<String, dynamic> toJson() => {
        "order_details": orderDetails?.toJson(),
      };
}

class OrderDetails {
  final dynamic id;
  final dynamic orderId;
  final String? paymentGateway;
  final dynamic serviceId;
  final String? serviceImage;
  final dynamic jobPostId;
  final dynamic providerId;
  final dynamic staffId;
  final dynamic adminId;
  final dynamic clientId;
  final dynamic clientImage;
  final DateTime? date;
  final String? schedule;
  final num basicPrice;
  final num subTotal;
  final num tax;
  final num total;
  final dynamic commissionType;
  final num commissionCharge;
  final num commissionAmount;
  final String? orderNote;
  final dynamic completeRequest;
  final String? paymentStatus;
  String? status;
  final List<SubOrderAddon> subOrderAddons;
  final Address? subOrderLocations;
  final Staff? staff;
  final ServiceModel? service;
  final String? serviceTitle;
  final Job? job;
  final Client? client;
  final List<OrderCompleteRequest>? completionHistory;
  final List<Review>? reviews;
  final RefundDetails? refundDetails;

  OrderDetails({
    this.id,
    this.orderId,
    this.paymentGateway,
    this.serviceId,
    this.serviceImage,
    this.serviceTitle,
    this.jobPostId,
    this.providerId,
    this.staffId,
    this.adminId,
    this.clientId,
    this.clientImage,
    this.date,
    this.schedule,
    required this.basicPrice,
    required this.subTotal,
    required this.tax,
    required this.total,
    required this.commissionType,
    required this.commissionCharge,
    required this.commissionAmount,
    this.orderNote,
    this.completeRequest,
    this.paymentStatus,
    this.status,
    required this.subOrderAddons,
    required this.subOrderLocations,
    this.staff,
    this.service,
    this.job,
    this.client,
    this.completionHistory,
    this.reviews,
    this.refundDetails,
  });

  factory OrderDetails.fromJson(Map<String, dynamic> json) => OrderDetails(
        id: json["id"],
        orderId: json["order_id"],
        paymentGateway: json["payment_gateway"],
        serviceId: json["service_id"],
        serviceImage: json["service_image"],
        jobPostId: json["job_post_id"],
        providerId: json["provider_id"],
        staffId: json["staff_id"],
        adminId: json["admin_id"],
        clientId: json["client_id"],
        clientImage: json["client_image"],
        serviceTitle: json["service_title"],
        date: DateTime.tryParse(json["date"].toString()),
        schedule: json["schedule"],
        basicPrice: json["basic_price"].toString().tryToParse,
        subTotal: json["sub_total"].toString().tryToParse,
        tax: json["tax"].toString().tryToParse,
        total: json["total"].toString().tryToParse,
        commissionType: json["commission_type"],
        commissionCharge: json["commission_charge"].toString().tryToParse,
        commissionAmount: json["commission_amount"].toString().tryToParse,
        orderNote: json["order_note"],
        completeRequest: json["complete_request"],
        paymentStatus: json["payment_status"]?.toString(),
        status: json["status"]?.toString(),
        subOrderAddons: json["subOrderAddons"] == null
            ? []
            : List<SubOrderAddon>.from(
                json["subOrderAddons"]!.map((x) => SubOrderAddon.fromJson(x))),
        completionHistory: json["order_complete_request"] == null
            ? []
            : List<OrderCompleteRequest>.from(json["order_complete_request"]!
                .map((x) => OrderCompleteRequest.fromJson(x))),
        subOrderLocations: json["subOrderLocations"] == null
            ? null
            : Address.fromJson(json["subOrderLocations"]),
        staff: json["staff"] == null ? null : Staff.fromJson(json["staff"]),
        service: json["service"] == null
            ? null
            : ServiceModel.fromJson(json["service"]),
        job: json["job"] == null ? null : Job.fromJson(json["job"]),
        client: json["client"] == null ? null : Client.fromJson(json["client"]),
        reviews: json["reviews"] == null
            ? []
            : List<Review>.from(
                json["reviews"]!.map((x) => Review.fromJson(x))),
        refundDetails: json["refund_details"] == null
            ? null
            : RefundDetails.fromJson(json["refund_details"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "order_id": orderId,
        "payment_gateway": paymentGateway,
        "service_id": serviceId,
        "service_image": serviceImage,
        "job_post_id": jobPostId,
        "provider_id": providerId,
        "staff_id": staffId,
        "admin_id": adminId,
        "client_id": clientId,
        "client_image": clientImage,
        "date":
            "${date!.year.toString().padLeft(4, '0')}-${date!.month.toString().padLeft(2, '0')}-${date!.day.toString().padLeft(2, '0')}",
        "schedule": schedule,
        "basic_price": basicPrice,
        "sub_total": subTotal,
        "tax": tax,
        "total": total,
        "commission_type": commissionType,
        "commission_charge": commissionCharge,
        "commission_amount": commissionAmount,
        "order_note": orderNote,
        "complete_request": completeRequest,
        "payment_status": paymentStatus,
        "status": status,
        "subOrderAddons":
            List<dynamic>.from(subOrderAddons.map((x) => x.toJson())),
        "staff": staff?.toJson(),
      };
}

class SubOrderAddon {
  final dynamic id;
  final dynamic subOrderId;
  final String? title;
  final num price;
  final num quantity;
  final dynamic status;

  SubOrderAddon({
    this.id,
    this.subOrderId,
    this.title,
    required this.price,
    required this.quantity,
    required this.status,
  });

  factory SubOrderAddon.fromJson(Map<String, dynamic> json) => SubOrderAddon(
        id: json["id"],
        subOrderId: json["sub_order_id"],
        title: json["title"],
        price: json["price"].toString().tryToParse,
        quantity: json["quantity"].toString().tryToParse,
        status: json["status"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "sub_order_id": subOrderId,
        "title": title,
        "price": price,
        "quantity": quantity,
        "status": status,
      };
}

class ServiceModel {
  final dynamic id;
  final Category? category;
  final dynamic subCategory;
  final dynamic childCategory;
  final String? title;
  final String? slug;
  final String? unit;
  final num price;
  final num discountPrice;
  num view;
  num soldCount;
  num totalReviews;
  num averageRating;

  final num avgRating;
  final dynamic isFeatured;
  final dynamic image;

  ServiceModel({
    this.id,
    this.category,
    this.subCategory,
    this.childCategory,
    this.title,
    this.slug,
    this.unit,
    this.price = 0,
    this.discountPrice = 0,
    this.avgRating = 0,
    required this.view,
    required this.soldCount,
    required this.totalReviews,
    required this.averageRating,
    this.isFeatured,
    this.image,
  });

  factory ServiceModel.fromJson(Map<String, dynamic> json) => ServiceModel(
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
        avgRating: json["discount_price"].toString().tryToParse,
        view: json["view"].toString().tryToParse,
        soldCount: json["sold_count"].toString().tryToParse,
        totalReviews: json["total_reviews"].toString().tryToParse,
        averageRating: json["average_rating"].toString().tryToParse,
        isFeatured: json["is_featured"],
        image: json["image"],
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
      };
}

class Review {
  final dynamic id;
  final dynamic userId;
  final dynamic reviewerId;
  final num rating;
  final dynamic orderId;
  final dynamic subOrderId;
  final dynamic serviceId;
  final dynamic jobId;
  final String? type;
  final String? message;
  final String? status;
  final DateTime? createdAt;
  final User? user;

  Review({
    this.id,
    this.userId,
    this.reviewerId,
    required this.rating,
    this.orderId,
    this.subOrderId,
    this.serviceId,
    this.jobId,
    this.type,
    this.message,
    this.status,
    this.createdAt,
    this.user,
  });

  factory Review.fromJson(Map<String, dynamic> json) => Review(
        id: json["id"],
        userId: json["user_id"],
        reviewerId: json["reviewer_id"],
        rating: json["rating"].toString().tryToParse,
        orderId: json["order_id"],
        subOrderId: json["sub_order_id"],
        serviceId: json["service_id"],
        jobId: json["job_id"],
        type: json["type"],
        message: json["message"],
        status: json["status"]?.toString(),
        createdAt: DateTime.tryParse(json["created_at"].toString()),
        user: json["user"] == null ? null : User.fromJson(json["user"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "user_id": userId,
        "reviewer_id": reviewerId,
        "rating": rating,
        "order_id": orderId,
        "sub_order_id": subOrderId,
        "service_id": serviceId,
        "job_id": jobId,
        "type": type,
        "message": message,
        "status": status,
        "created_at": createdAt?.toIso8601String(),
        "user": user?.toJson(),
      };
}

class User {
  final dynamic id;
  final String? fullName;
  final String? image;

  User({
    this.id,
    this.fullName,
    this.image,
  });

  factory User.fromJson(Map<String, dynamic> json) => User(
        id: json["id"],
        fullName: json["full_name"],
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "full_name": fullName,
        "image": image,
      };
}

class RefundDetails {
  final dynamic id;
  final String? cancelReason;
  final dynamic gatewayId;
  final String? gatewayName;
  final dynamic gatewayFields;
  final dynamic amount;
  final dynamic status;
  final DateTime? createdAt;

  RefundDetails({
    required this.id,
    required this.cancelReason,
    required this.gatewayId,
    required this.gatewayName,
    required this.gatewayFields,
    required this.amount,
    required this.status,
    required this.createdAt,
  });

  factory RefundDetails.fromJson(Map<String, dynamic> json) => RefundDetails(
        id: json["id"],
        cancelReason: json["cancel_reason"],
        gatewayId: json["gateway_id"],
        gatewayName: json["gateway_name"],
        gatewayFields: json["gateway_fields"],
        amount: json["amount"].toString().tryToParse,
        status: json["status"]?.toString(),
        createdAt: DateTime.parse(json["created_at"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "cancel_reason": cancelReason,
        "gateway_id": gatewayId,
        "gateway_name": gatewayName,
        "gateway_fields": gatewayFields,
        "amount": amount,
        "status": status,
        "created_at": createdAt,
      };
}
