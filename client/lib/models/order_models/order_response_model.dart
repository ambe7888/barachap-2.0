import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/models/job/job_list_model.dart';

import '../home_models/services_list_model.dart';
import '../provider_model.dart';
import '../service/service_details_model.dart';

OrderResponseModel orderResponseModelFromJson(String str) =>
    OrderResponseModel.fromJson(json.decode(str));

String orderResponseModelToJson(OrderResponseModel data) =>
    json.encode(data.toJson());

class OrderResponseModel {
  final OrderDetails? orderDetails;

  OrderResponseModel({this.orderDetails});

  factory OrderResponseModel.fromJson(Map json) => OrderResponseModel(
    orderDetails:
        (json["order_details"] ?? json["all_services"]) == null
            ? null
            : OrderDetails.fromJson(
              json["order_details"] ?? json["all_services"],
            ),
  );

  Map<String, dynamic> toJson() => {"order_details": orderDetails?.toJson()};
}

class OrderDetails {
  final dynamic id;
  final dynamic userId;
  final num subTotal;
  final num tax;
  final num total;
  final dynamic couponCode;
  final dynamic couponType;
  final num couponAmount;
  final String? paymentGateway;
  String? paymentStatus;
  final dynamic transactionId;
  final String? invoiceNumber;
  final dynamic commissionType;
  final dynamic commissionCharge;
  final dynamic commissionAmount;
  final dynamic status;
  final DateTime? createdAt;
  final List<SubOrder>? subOrders;

  OrderDetails({
    this.id,
    this.userId,
    required this.subTotal,
    required this.tax,
    required this.total,
    this.couponCode,
    this.couponType,
    required this.couponAmount,
    this.paymentGateway,
    this.paymentStatus,
    this.transactionId,
    this.invoiceNumber,
    this.commissionType,
    this.commissionCharge,
    this.commissionAmount,
    this.status,
    this.createdAt,
    this.subOrders,
  });

  factory OrderDetails.fromJson(Map<String, dynamic> json) => OrderDetails(
    id: json["id"],
    userId: json["user_id"],
    subTotal: json["sub_total"].toString().tryToParse,
    tax: json["tax"].toString().tryToParse,
    total: json["total"].toString().tryToParse,
    couponCode: json["coupon_code"],
    couponType: json["coupon_type"],
    couponAmount: json["coupon_amount"].toString().tryToParse,
    paymentGateway: json["payment_gateway"],
    paymentStatus: json["payment_status"]?.toString(),
    transactionId: json["transaction_id"],
    invoiceNumber: json["invoice_number"],
    commissionType: json["commission_type"],
    commissionCharge: json["commission_charge"],
    commissionAmount: json["commission_amount"],
    status: json["status"],
    createdAt: DateTime.tryParse(json["created_at"].toString()),
    subOrders:
        json["subOrders"] == null
            ? []
            : List<SubOrder>.from(
              json["subOrders"]!.map((x) => SubOrder.fromJson(x)),
            ),
  );

  Map<String, dynamic> toJson() => {
    "id": id,
    "user_id": userId,
    "sub_total": subTotal,
    "tax": tax,
    "total": total,
    "coupon_code": couponCode,
    "coupon_type": couponType,
    "coupon_amount": couponAmount,
    "payment_gateway": paymentGateway,
    "payment_status": paymentStatus,
    "transaction_id": transactionId,
    "invoice_number": invoiceNumber,
    "commission_type": commissionType,
    "commission_charge": commissionCharge,
    "commission_amount": commissionAmount,
    "status": status,
    "created_at": createdAt,
    "subOrders":
        subOrders == null
            ? []
            : List<dynamic>.from(subOrders!.map((x) => x.toJson())),
  };
}

class SubOrder {
  final dynamic id;
  final dynamic orderId;
  final dynamic serviceId;
  final dynamic jobPostId;
  final dynamic providerId;
  final dynamic staffId;
  final dynamic adminId;
  final dynamic clientId;
  final DateTime? date;
  final String? schedule;
  final num basicPrice;
  final num subTotal;
  final num tax;
  final num total;
  final String? commissionType;
  final dynamic commissionCharge;
  final num commissionAmount;
  final String? orderNote;
  final dynamic completeRequest;
  final dynamic paymentStatus;
  String? status;
  final List<Addon> subOrderAddons;
  final Address? subOrderLocations;
  final Staff? staff;
  final ServiceModel? service;
  final Job? job;
  final List<Review>? reviews;
  final ProviderModel? provider;
  final List<OrderCompleteRequest>? completionHistory;
  final RefundDetails? refundDetails;

  SubOrder({
    this.id,
    this.orderId,
    this.serviceId,
    this.jobPostId,
    this.providerId,
    this.staffId,
    this.adminId,
    this.clientId,
    this.date,
    this.schedule,
    required this.basicPrice,
    required this.subTotal,
    required this.tax,
    required this.total,
    this.commissionType,
    required this.commissionCharge,
    required this.commissionAmount,
    this.orderNote,
    this.completeRequest,
    this.paymentStatus,
    this.status,
    required this.subOrderAddons,
    this.subOrderLocations,
    this.staff,
    this.reviews,
    this.service,
    this.job,
    this.provider,
    this.completionHistory,
    this.refundDetails,
  });

  factory SubOrder.fromJson(Map<String, dynamic> json) => SubOrder(
    id: json["id"],
    orderId: json["order_id "],
    serviceId: json["service_id "],
    jobPostId: json["job_post_id"],
    providerId: json["provider_id"],
    staffId: json["staff_id"],
    adminId: json["admin_id"],
    clientId: json["client_id"],
    date: json["date"] == null ? null : DateTime.parse(json["date"]),
    schedule: json["schedule"],
    basicPrice: json["basic_price"].toString().tryToParse,
    subTotal: json["sub_total"].toString().tryToParse,
    tax: json["tax"].toString().tryToParse,
    total: json["total"].toString().tryToParse,
    commissionType: json["commission_type"],
    commissionCharge: json["commission_charge"],
    commissionAmount: json["commission_amount"].toString().tryToParse,
    orderNote: json["order_note"],
    completeRequest: json["complete_request"],
    paymentStatus: json["payment_status"],
    job: json["job"] == null ? null : Job.fromJson(json["job"]),
    provider:
        json["provider"] == null
            ? null
            : ProviderModel.fromJson(json["provider"]),
    status: json["status"].toString(),
    subOrderAddons:
        json["subOrderAddons"] == null
            ? []
            : List<Addon>.from(
              json["subOrderAddons"]!.map((x) => Addon.fromJson(x)),
            ),
    subOrderLocations:
        json["subOrderLocations"] == null
            ? null
            : Address.fromJson(json["subOrderLocations"]),
    staff: json["staff"] == null ? null : Staff.fromJson(json["staff"]),
    completionHistory:
        json["order_complete_request"] == null
            ? []
            : List<OrderCompleteRequest>.from(
              json["order_complete_request"]!.map(
                (x) => OrderCompleteRequest.fromJson(x),
              ),
            ),
    service:
        json["service"] == null ? null : ServiceModel.fromJson(json["service"]),
    reviews:
        json["reviews"] == null
            ? []
            : List<Review>.from(
              json["reviews"]!.map((x) => Review.fromJson(x)),
            ),
    refundDetails:
        json["refund_details"] == null
            ? null
            : RefundDetails.fromJson(json["refund_details"]),
  );

  Map<String, dynamic> toJson() => {
    "id": id,
    "order_id ": orderId,
    "service_id ": serviceId,
    "job_post_id": jobPostId,
    "provider_id": providerId,
    "staff_id": staffId,
    "admin_id": adminId,
    "client_id": clientId,
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
        subOrderAddons == null
            ? []
            : List<dynamic>.from(subOrderAddons.map((x) => x.toJson())),
    "staff": staff?.toJson(),
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

  User({this.id, this.fullName, this.image});

  factory User.fromJson(Map<String, dynamic> json) =>
      User(id: json["id"], fullName: json["full_name"], image: json["image"]);

  Map<String, dynamic> toJson() => {
    "id": id,
    "full_name": fullName,
    "image": image,
  };
}

class OrderCompleteRequest {
  final dynamic id;
  final dynamic orderId;
  final dynamic subOrderId;
  final dynamic clientId;
  final dynamic providerId;
  String? message;
  String status;
  final dynamic image;
  final DateTime? createdAt;

  OrderCompleteRequest({
    this.id,
    this.orderId,
    this.subOrderId,
    this.clientId,
    this.providerId,
    this.message,
    this.status = "",
    this.image,
    this.createdAt,
  });

  factory OrderCompleteRequest.fromJson(Map<String, dynamic> json) =>
      OrderCompleteRequest(
        id: json["id"],
        orderId: json["order_id"],
        subOrderId: json["sub_order_id"],
        clientId: json["client_id"],
        providerId: json["provider_id"],
        message: json["message"],
        status: json["status"].toString(),
        image: json["image"],
        createdAt: DateTime.tryParse(json["created_at"].toString()),
      );

  Map<String, dynamic> toJson() => {
    "id": id,
    "order_id": orderId,
    "sub_order_id": subOrderId,
    "client_id": clientId,
    "provider_id": providerId,
    "message": message,
    "status": status,
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
