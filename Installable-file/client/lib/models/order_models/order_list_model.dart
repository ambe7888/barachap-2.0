import 'dart:convert';

import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../address_models/states_model.dart';

OrderListModel orderResponseModelFromJson(String str) =>
    OrderListModel.fromJson(json.decode(str));

String orderResponseModelToJson(OrderListModel data) =>
    json.encode(data.toJson());

class OrderListModel {
  final List<Order> orders;
  final Pagination? pagination;

  OrderListModel({
    required this.orders,
    this.pagination,
  });

  factory OrderListModel.fromJson(Map json) => OrderListModel(
        orders: json["all_services"] == null || json["all_services"] is! List
            ? []
            : List<Order>.from(
                json["all_services"]!.map((x) => Order.fromJson(x))),
        pagination: json["pagination"] == null
            ? null
            : Pagination.fromJson(json["pagination"]),
      );

  Map<String, dynamic> toJson() => {
        "all_services": List<dynamic>.from(orders.map((x) => x.toJson())),
      };
}

class Order {
  final dynamic id;
  final dynamic userId;
  final num subTotal;
  final num tax;
  final num total;
  final dynamic couponCode;
  final dynamic couponType;
  final dynamic couponAmount;
  final String? paymentGateway;
  final String? paymentStatus;
  final dynamic transactionId;
  final String? invoiceNumber;
  final String? status;
  final String? type;
  final DateTime? createdAt;

  Order({
    this.id,
    this.userId,
    required this.subTotal,
    required this.tax,
    required this.total,
    this.couponCode,
    this.couponType,
    this.couponAmount,
    this.paymentGateway,
    this.paymentStatus,
    this.transactionId,
    this.invoiceNumber,
    this.status,
    this.type,
    this.createdAt,
  });

  factory Order.fromJson(Map<String, dynamic> json) => Order(
        id: json["id"],
        userId: json["user_id"],
        subTotal: json["sub_total"].toString().tryToParse,
        tax: json["tax"].toString().tryToParse,
        total: json["total"].toString().tryToParse,
        couponCode: json["coupon_code"],
        couponType: json["coupon_type"],
        couponAmount: json["coupon_amount"],
        paymentGateway: json["payment_gateway"]?.toString(),
        paymentStatus: json["payment_status"]?.toString(),
        transactionId: json["transaction_id"]?.toString(),
        invoiceNumber: json["invoice_number"]?.toString(),
        status: json["status"]?.toString(),
        type: json["type"]?.toString(),
        createdAt: DateFormat("dd-MM-yyyy").tryParse(json["created_at"]),
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
        "transaction_id": transactionId,
        "invoice_number": invoiceNumber,
        "status": status,
      };
}
