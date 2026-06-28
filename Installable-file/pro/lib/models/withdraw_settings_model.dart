// To parse this JSON data, do
//
//     final withdrawSettingsModel = withdrawSettingsModelFromJson(jsonString);

import 'dart:convert';

import 'package:prohand/helper/extension/string_extension.dart';

WithdrawSettingsModel withdrawSettingsModelFromJson(String str) =>
    WithdrawSettingsModel.fromJson(json.decode(str));

String withdrawSettingsModelToJson(WithdrawSettingsModel data) =>
    json.encode(data.toJson());

class WithdrawSettingsModel {
  List<WithdrawGateway> withdrawGateways;
  UserCurrentBalance userCurrentBalance;
  String? minimumWithdrawAmount;
  String? maximumWithdrawAmount;
  String? withdrawFeeType;
  String? withdrawFee;

  WithdrawSettingsModel({
    required this.withdrawGateways,
    required this.userCurrentBalance,
    this.minimumWithdrawAmount,
    this.maximumWithdrawAmount,
    this.withdrawFeeType,
    this.withdrawFee,
  });

  factory WithdrawSettingsModel.fromJson(json) => WithdrawSettingsModel(
        withdrawGateways: json["withdraw_gateways"] == null
            ? []
            : List<WithdrawGateway>.from(json["withdraw_gateways"]!
                .map((x) => WithdrawGateway.fromJson(x))),
        userCurrentBalance: json["user_current_balance"] == null
            ? UserCurrentBalance(balance: 0)
            : UserCurrentBalance.fromJson(json["user_current_balance"]),
        minimumWithdrawAmount: json["minimum_withdraw_amount"],
        maximumWithdrawAmount: json["maximum_withdraw_amount"],
        withdrawFeeType: json["withdraw_fee_type"],
        withdrawFee: json["withdraw_fee"],
      );

  Map<String, dynamic> toJson() => {
        "withdraw_gateways":
            List<dynamic>.from(withdrawGateways.map((x) => x.toJson())),
        "user_current_balance": userCurrentBalance.toJson(),
        "minimum_withdraw_amount": minimumWithdrawAmount,
        "maximum_withdraw_amount": maximumWithdrawAmount,
        "withdraw_fee_type": withdrawFeeType,
        "withdraw_fee": withdrawFee,
      };
}

class UserCurrentBalance {
  num balance;

  UserCurrentBalance({
    required this.balance,
  });

  factory UserCurrentBalance.fromJson(Map<String, dynamic> json) =>
      UserCurrentBalance(
        balance: json["balance"].toString().tryToParse,
      );

  Map<String, dynamic> toJson() => {
        "balance": balance,
      };
}

class WithdrawGateway {
  final id;
  String name;
  List<String> field;

  WithdrawGateway({
    this.id,
    required this.name,
    required this.field,
  });

  factory WithdrawGateway.fromJson(Map<String, dynamic> json) =>
      WithdrawGateway(
        id: json["id"],
        name: json["name"] ?? "",
        field: json["field"] == null
            ? []
            : List<String>.from((json["field"] ?? "").map((x) => x)),
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "field": List<dynamic>.from(field.map((x) => x)),
      };
}
