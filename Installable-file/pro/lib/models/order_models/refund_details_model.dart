import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/order_models/order_list_model.dart';

import '../../models/order_models/order_response_model.dart';

RefundDetailsModel refundDetailsModelFromJson(String str) =>
    RefundDetailsModel.fromJson(json.decode(str));

String refundDetailsModelToJson(RefundDetailsModel data) =>
    json.encode(data.toJson());

class RefundDetailsModel {
  final RefundDetails? refundDetails;

  RefundDetailsModel({this.refundDetails});

  factory RefundDetailsModel.fromJson(Map json) => RefundDetailsModel(
        refundDetails: json["refunded_order"] == null
            ? null
            : RefundDetails.fromJson(json["refunded_order"]),
      );

  Map<String, dynamic> toJson() => {
        "refunded_order": refundDetails?.toJson(),
      };
}

class RefundDetails {
  final dynamic id;
  final dynamic orderId;
  final dynamic suborderId;
  final dynamic user;
  final OrderDetails? order;
  final num amount;
  final String? cancelReason;
  final dynamic gatewayId;
  final dynamic gatewayName;
  final Map? gatewayFields;
  final dynamic image;
  final String status;
  final DateTime? createdAt;
  final Order? refundOrder;

  RefundDetails({
    this.id,
    this.user,
    this.order,
    this.amount = 0,
    this.cancelReason,
    this.gatewayId,
    this.gatewayName,
    this.gatewayFields,
    this.image,
    this.status = "",
    this.createdAt,
    this.refundOrder,
    this.orderId,
    this.suborderId,
  });

  factory RefundDetails.fromJson(Map<String, dynamic> json) => RefundDetails(
        id: json["id"],
        user: json["user"],
        order:
            json["order"] == null ? null : OrderDetails.fromJson(json["order"]),
        amount: json["amount"].toString().tryToParse,
        cancelReason: json["cancel_reason"],
        gatewayId: json["gateway_id"],
        image: json["image"],
        status: json["status"].toString(),
        createdAt: json["created_at"] == null
            ? null
            : DateTime.parse(json["created_at"]),
        refundOrder:
            json["order"] == null ? null : Order.fromJson(json["order"]),
        orderId: json["order_id"],
        suborderId: json["sub_order_id"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "user": user,
        "order": order?.toJson(),
        "amount": amount,
        "cancel_reason": cancelReason,
        "gateway_id": gatewayId,
        "image": image,
        "status": status,
        "created_at": createdAt?.toIso8601String(),
      };
}
