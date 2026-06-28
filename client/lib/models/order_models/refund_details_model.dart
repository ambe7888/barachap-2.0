import 'dart:convert';

import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../models/order_models/order_response_model.dart';

RefundDetailsModel refundDetailsModelFromJson(String str) =>
    RefundDetailsModel.fromJson(json.decode(str));

String refundDetailsModelToJson(RefundDetailsModel data) =>
    json.encode(data.toJson());

class RefundDetailsModel {
  final RefundDetails? refundDetails;

  RefundDetailsModel({this.refundDetails});

  factory RefundDetailsModel.fromJson(Map json) => RefundDetailsModel(
    refundDetails:
        json["refunded_order_details"] == null
            ? null
            : RefundDetails.fromJson(json["refunded_order_details"]),
  );

  Map<String, dynamic> toJson() => {
    "refunded_order_details": refundDetails?.toJson(),
  };
}

class RefundDetails {
  final dynamic id;
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
  });

  factory RefundDetails.fromJson(Map<String, dynamic> json) => RefundDetails(
    id: json["id"],
    user: json["user"],
    order: json["order"] == null ? null : OrderDetails.fromJson(json["order"]),
    amount: json["amount"].toString().tryToParse,
    cancelReason: json["cancel_reason"],
    gatewayId: json["gateway_id"],
    gatewayName: json["gateway_name"],
    gatewayFields: json["gateway_fields"] ?? {},
    image: json["image"],
    status: json["status"].toString(),
    createdAt:
        json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
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
