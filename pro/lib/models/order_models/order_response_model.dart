import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';

import '../service_models/service_details_model.dart';
import '../staff_models/staff_list_model.dart';

OrderResponseModel orderResponseModelFromJson(String str) =>
    OrderResponseModel.fromJson(json.decode(str));

String orderResponseModelToJson(OrderResponseModel data) =>
    json.encode(data.toJson());

class OrderResponseModel {
  final OrderDetails? orderDetails;

  OrderResponseModel({
    this.orderDetails,
  });

  factory OrderResponseModel.fromJson(Map json) => OrderResponseModel(
        orderDetails: (json["order_details"] ?? json["all_services"]) == null
            ? null
            : OrderDetails.fromJson(
                json["order_details"] ?? json["all_services"]),
      );

  Map<String, dynamic> toJson() => {
        "order_details": orderDetails?.toJson(),
      };
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
        subOrders: json["subOrders"] == null
            ? []
            : List<SubOrder>.from(
                json["subOrders"]!.map((x) => SubOrder.fromJson(x))),
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
        "subOrders": subOrders == null
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
  final String? status;
  final List<Addon> subOrderAddons;
  final List<Address>? subOrderLocations;
  final Staff? staff;

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
        status: json["status"].toString(),
        subOrderAddons: json["subOrderAddons"] == null
            ? []
            : List<Addon>.from(
                json["subOrderAddons"]!.map((x) => Addon.fromJson(x))),
        subOrderLocations: json["subOrderLocations"] == null
            ? []
            : List<Address>.from(
                json["subOrderLocations"]!.map((x) => Address.fromJson(x))),
        staff: json["staff"] == null ? null : Staff.fromJson(json["staff"]),
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
        "subOrderAddons": subOrderAddons == null
            ? []
            : List<dynamic>.from(subOrderAddons.map((x) => x.toJson())),
        "subOrderLocations": subOrderLocations == null
            ? []
            : List<dynamic>.from(subOrderLocations!.map((x) => x.toJson())),
        "staff": staff?.toJson(),
      };
}
