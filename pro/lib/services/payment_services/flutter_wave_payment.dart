import 'package:flutter/material.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

class FlutterWavePayment {
  late BuildContext context;
  String currency = dProvider.currencyCode;

  void makePayment(BuildContext context, publicKey, secretKey, double amount,
      userName, userPhone, userMail, onSuccess, onFailed) async {
    this.context = context;
    _handlePaymentInitialization(context, publicKey, secretKey, amount,
        userName, userPhone, userMail, onSuccess, onFailed);
  }

  _handlePaymentInitialization(BuildContext context, publicKey, secretKey,
      double amount, userName, userPhone, userMail, onSuccess, onFailed) async {
    debugPrint(publicKey.toString());
    debugPrint(secretKey.toString());
    if (publicKey == null || secretKey == null) {
      LocalKeys.invalidDeveloperKeys.showToast();
    }
    onFailed();
  }
}
