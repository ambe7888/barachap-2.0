import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';
import 'package:prohand/models/service_models/service_details_model.dart';
import 'package:prohand/models/staff_models/staff_list_model.dart';

import '../service_models/service_list_model.dart';

OrderListModel orderListModelFromJson(String str) =>
    OrderListModel.fromJson(json.decode(str));

String orderListModelToJson(OrderListModel data) => json.encode(data.toJson());

class OrderListModel {
  final List<Order> allOrders;
  final Pagination? pagination;

  OrderListModel({
    required this.allOrders,
    this.pagination,
  });

  factory OrderListModel.fromJson(Map json) => OrderListModel(
        allOrders: (json["all_orders"] ?? json["today_orders"]) == null
            ? []
            : List<Order>.from((json["all_orders"] ?? json["today_orders"])!
                .map((x) => Order.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "all_orders": allOrders == null
            ? []
            : List<dynamic>.from(allOrders.map((x) => x.toJson())),
      };
}

class Order {
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
  final String? commissionType;
  final num commissionCharge;
  final num commissionAmount;
  final String? orderNote;
  final dynamic completeRequest;
  final String? paymentStatus;
  final String? status;
  final List<Addon>? subOrderAddons;
  final Address? subOrderLocations;
  final Staff? staff;

  Order({
    this.id,
    this.orderId,
    this.paymentGateway,
    this.serviceId,
    this.serviceImage,
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
    this.commissionType,
    required this.commissionCharge,
    required this.commissionAmount,
    this.orderNote,
    this.completeRequest,
    this.paymentStatus,
    this.status,
    this.subOrderAddons,
    this.subOrderLocations,
    this.staff,
  });

  factory Order.fromJson(Map<String, dynamic> json) => Order(
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
        date: json["date"] == null ? null : DateTime.parse(json["date"]),
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
            : List<Addon>.from(
                json["subOrderAddons"]!.map((x) => Addon.fromJson(x))),
        subOrderLocations: json["subOrderLocations"] == null
            ? null
            : Address.fromJson(json["subOrderLocations"]),
        staff: json["staff"] == null ? null : Staff.fromJson(json["staff"]),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "service_id": serviceId,
        "service_image": serviceImage,
        "job_post_id": jobPostId,
        "provider_id": providerId,
        "staff_id": staffId,
        "admin_id": adminId,
        "client_id": clientId,
        "client_image": clientImage,
        "staff": staff?.toJson(),
      };
}
