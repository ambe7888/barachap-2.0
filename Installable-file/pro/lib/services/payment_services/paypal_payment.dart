import 'dart:convert';
import 'dart:core';
import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

import '../../utils/components/alerts.dart';
import '../../utils/components/custom_preloader.dart';
import '../../utils/components/navigation_pop_icon.dart';
import 'paypal_service.dart';

class PaypalPayment extends StatefulWidget {
  final Function onSuccess;
  final Function onFailed;
  final String clientSecret;
  final String clientId;
  final amount;

  final currencyCode;

  const PaypalPayment(
      {super.key,
      required this.onSuccess,
      required this.onFailed,
      this.amount,
      this.currencyCode,
      required this.clientSecret,
      required this.clientId});

  @override
  State<StatefulWidget> createState() {
    return PaypalPaymentState();
  }
}

class PaypalPaymentState extends State<PaypalPayment> {
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();
  var checkoutUrl;
  var executeUrl;
  var accessToken;
  PaypalServices services = PaypalServices();

  Map<dynamic, dynamic> defaultCurrency = {
    "symbol": "USD",
    "decimalDigits": 2,
    "symbolBeforeTheNumber": true,
    "currency": "USD"
  };

  bool isEnableShipping = false;
  bool isEnableAddress = false;

  String returnURL = 'return.example.com';
  String cancelURL = 'cancel.example.com';

  @override
  void initState() {
    super.initState();

    Future.delayed(Duration.zero, () async {
      accessToken = await services.getAccessToken(
        context,
        widget.clientId,
        widget.clientSecret,
      );

      final transactions = getOrderParams(
        context,
        widget.amount,
        widget.currencyCode,
      );
      final res = await services.createPaypalPayment(transactions, accessToken);
      setState(() {
        checkoutUrl = res["approvalUrl"] ?? res["executeUrl"];
        executeUrl = res["executeUrl"];
      });
    });
  }

  Map<String, dynamic> getOrderParams(
      BuildContext context, amount, currencyCode) {
    Map<String, dynamic> temp = {
      "intent": "sale",
      "payer": {"payment_method": "paypal"},
      "transactions": [
        {
          "amount": {
            "total": amount,
            "currency": currencyCode,
          },
        }
      ],
      "note_to_payer": "Contact us for any questions on your order.",
      "redirect_urls": {
        "return_url": "return.example.com",
        "cancel_url": "cancel.example.com"
      }
    };

    _controller
      ..loadRequest(Uri.parse(checkoutUrl ?? ''))
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(NavigationDelegate(
        onProgress: (int progress) {},
        onPageStarted: (String url) {},
        onPageFinished: (String url) async {},
        onWebResourceError: (WebResourceError error) {},
        onNavigationRequest: (request) {
          if (request.url.contains(returnURL)) {
            final uri = Uri.parse(request.url);
            final payerID = uri.queryParameters['PayerID'];
            print(payerID);
            if (payerID != null) {
              services
                  .executePayment(executeUrl, payerID, accessToken)
                  .then((id) async {
                widget.onSuccess();
              });
            } else {
              widget.onFailed();
            }
          }
          if (request.url.contains(cancelURL)) {
            widget.onFailed();
          }
          return NavigationDecision.navigate;
        },
      ));
    return temp;
  }

  final WebViewController _controller = WebViewController();

  @override
  Widget build(BuildContext context) {
    print(checkoutUrl);

    if (checkoutUrl != null) {
      return Scaffold(
        appBar: AppBar(
          leading: NavigationPopIcon(onTap: () async {
            Alerts().paymentFailWarning(context);
          }),
        ),
        body: WillPopScope(
          onWillPop: () async {
            bool canGoBack = await _controller.canGoBack();
            if (canGoBack) {
              _controller.goBack();
              return false;
            }
            Alerts().paymentFailWarning(context);
            return false;
          },
          child: WebViewWidget(
            controller: _controller,
          ),
        ),
      );
    } else {
      return Scaffold(
        appBar: AppBar(
          leading: NavigationPopIcon(onTap: () async {
            Alerts().paymentFailWarning(context);
          }),
        ),
        body: WillPopScope(
            onWillPop: () async {
              bool canGoBack = await _controller.canGoBack();
              if (canGoBack) {
                _controller.goBack();
                return false;
              }
              Alerts().paymentFailWarning(context);
              return false;
            },
            child: Center(
                child: Container(
                    child: const SizedBox(
              height: 60,
              child: CustomPreloader(),
            )))),
        floatingActionButton: IconButton(
            onPressed: () {
              Future.delayed(Duration.zero, () async {
                accessToken = await services.getAccessToken(
                  context,
                  widget.clientId,
                  widget.clientSecret,
                );

                final transactions = getOrderParams(
                  context,
                  widget.amount,
                  widget.currencyCode,
                );
                debugPrint("got transaction params".toString());
                final res = await services.createPaypalPayment(
                    transactions, accessToken);
                debugPrint("got res".toString());
                setState(() {
                  checkoutUrl = res["approvalUrl"] ?? res["executeUrl"];
                  log(jsonEncode(res));
                  executeUrl = res["executeUrl"];
                });
              });
            },
            icon: const Icon(Icons.reset_tv_sharp)),
      );
    }
  }
}
