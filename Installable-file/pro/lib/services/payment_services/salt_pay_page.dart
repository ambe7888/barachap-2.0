// // ignore_for_file: prefer_typing_uninitialized_variables

// import 'dart:async';
// import 'dart:convert';

// import 'package:crypto/crypto.dart';
// import 'package:flutter/material.dart';
// import 'package:flutter/services.dart';
// import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
// import 'package:fluttertoast/fluttertoast.dart';
// import 'package:provider/provider.dart';
// import 'package:webview_flutter/webview_flutter.dart';
// import 'package:http/http.dart' as http;

// class SaltPayPayment extends StatelessWidget {
//   SaltPayPayment(
//       {Key? wKey,
//       required this.amount,
//       this.serviceId,
//       required this.jobId,
//       required this.email,
//       this.payAgain = false,
//       this.orderId,
//       required this.isFromOrderExtraAccept,
//       required this.isFromWalletDeposite,
//       required this.isFromHireJob})
//       : super(key: wKey);

//   final amount;
//   final serviceId;
//   final jobId;
//   final email;
//   bool payAgain;
//   final orderId;
//   final isFromOrderExtraAccept;
//   final isFromWalletDeposite;
//   final isFromHireJob;

//   String? url;
//   String? html;
//   late WebViewController _controller;
//   @override
//   Widget build(BuildContext context) {
//     Future.delayed(const Duration(microseconds: 600), () {
//       Provider.of<PlaceOrderService>(context, listen: false).setLoadingFalse();
//     });
//     String successUrl = "https://bokanu.is/success";
//     String failedUrl = "https://bokanu.is/failed";

//     return Scaffold(
//       appBar: CommonHelper().appbarCommon('Salt-Pay', context, () {
//         Provider.of<PlaceOrderService>(context, listen: false)
//             .doNext(context, 'failed', paymentFailed: true);
//       }),
//       body: WillPopScope(
//           onWillPop: () async {
//             await Provider.of<PlaceOrderService>(context, listen: false)
//                 .doNext(context, 'failed', paymentFailed: true);
//             return true;
//           },
//           child: WebView(
//             onWebViewCreated: (controller) {
//               final now = DateTime.now();
//               final pg = Provider.of<PaymentGatewayListService>(context,
//                   listen: false);
//               final merchantId = pg.merchantId;
//               final secret = pg.secretKey;
//               final newOID =
//                   "${orderId ?? jobId}${now.millisecond}${now.microsecond}${now.millisecond}${now.millisecond}${now.microsecond}${now.millisecond}${now.millisecond}${now.microsecond}${now.millisecond}${now.microsecond}${now.millisecond}${now.microsecond}${now.millisecond}";

//
//               final bodyS = [
//                 merchantId.toString(),
//                 successUrl,
//                 successUrl,
//                 newOID.substring(0, 13),
//                 (num.tryParse(amount.toString()) ?? 0).toStringAsFixed(2),
//                 rtlProvider.currencyCode ?? "USD",
//               ];
//               final message = bodyS.join("|");
//               final anotherData =
//                   _getHmacHashInBase64FromString(secret, message);

//               debugPrint(anotherData.toString());
//               debugPrint(secret.toString());
//               debugPrint(merchantId.toString());
//               debugPrint(pg.gatewayId.toString());
//               final body = ({
//                 'merchantId': merchantId.toString(),
//                 'paymentgatewayid': pg.gatewayId ?? "16",
//                 'checkhash': anotherData.toString(),
//                 'orderid': newOID.substring(0, 13),
//                 'reference': 'order',
//                 'currency': rtlProvider.currencyCode ?? "USD",
//                 'language': pg.lang,
//                 'buyermail': email ?? "",
//                 'returnurlsuccess': 'https://bokanu.is/success',
//                 'returnurlsuccessserver': 'https://bokanu.is/success',
//                 'returnurlcancel': failedUrl,
//                 'returnurlerror': failedUrl,
//                 'amount':
//                     (num.tryParse(amount.toString()) ?? 0).toStringAsFixed(2),
//                 'pagetype': '0',
//                 'skipreceiptpage': '1',
//                 'itemdescription_0': serviceId != null
//                     ? "A payment for Service pay- $serviceId"
//                     : "A payment for Job pay- $jobId",
//                 'itemcount_0': '1',
//                 'itemunitamount_0':
//                     (num.tryParse(amount.toString()) ?? 0).toStringAsFixed(2),
//                 'itemamount_0':
//                     (num.tryParse(amount.toString()) ?? 0).toStringAsFixed(2)
//               });
//               String uniString = "";
//               body.forEach((key, value) {
//                 if (key != 'itemamount_0') {
//                   uniString = "$uniString$key=$value&";
//                 } else {
//                   uniString = "$uniString$key=$value";
//                 }
//               });

//               debugPrint("uni String is $uniString".toString());
//               debugPrint(
//                   "unir String is ${'https://${pg.isTestMode.toString() == "true" ? "test" : "securepay"}.borgun.is/securepay/default.aspx'}"
//                       .toString());
//               final headers = {'Content-Type': 'text/xml'};
//               controller.loadRequest(WebViewRequest(
//                   uri: Uri.parse(
//                       'https://${pg.isTestMode.toString() == "true" ? "test" : "securepay"}.borgun.is/securepay/default.aspx'),
//                   method: WebViewRequestMethod.post,
//                   headers: headers,
//                   body: Uint8List.fromList(utf8.encode(uniString))));
//
//             },
//             initialUrl: baseApi.replaceAll("api/v1", ""),
//             javascriptMode: JavascriptMode.unrestricted,
//             onPageStarted: (url) async {
//               debugPrint(url.toString());
//               debugPrint(
//                   "page url---------------------------------------".toString());
//               if (url.toString().contains(successUrl)) {
//                 if (isFromOrderExtraAccept == true) {
//                   await Provider.of<OrderDetailsService>(context, listen: false)
//                       .acceptOrderExtra(context);
//                 } else if (isFromWalletDeposite) {
//                   await Provider.of<WalletService>(context, listen: false)
//                       .makeDepositeToWalletSuccess(context);
//                 } else if (isFromHireJob) {
//                   Provider.of<JobRequestService>(context, listen: false)
//                       .goToJobSuccessPage(context);
//                 } else {
//                   await Provider.of<PlaceOrderService>(context, listen: false)
//                       .makePaymentSuccess(context, oId: orderId);
//                 }
//                 return;
//               } else if (url.toString().contains(failedUrl)) {
//                 await Provider.of<PlaceOrderService>(context, listen: false)
//                     .doNext(context, 'failed', paymentFailed: true);
//                 return;
//               } else {
//                 return;
//               }
//             },
//             navigationDelegate: (request) async {
//               debugPrint(request.url.toString());
//

//               debugPrint(
//                   "url---------------------------------------".toString());
//               if (request.url.toString().contains(successUrl)) {
//                 if (isFromOrderExtraAccept == true) {
//                   await Provider.of<OrderDetailsService>(context, listen: false)
//                       .acceptOrderExtra(context);
//                 } else if (isFromWalletDeposite) {
//                   await Provider.of<WalletService>(context, listen: false)
//                       .makeDepositeToWalletSuccess(context);
//                 } else if (isFromHireJob) {
//                   Provider.of<JobRequestService>(context, listen: false)
//                       .goToJobSuccessPage(context);
//                 } else {
//                   await Provider.of<PlaceOrderService>(context, listen: false)
//                       .makePaymentSuccess(context, oId: orderId);
//                 }
//                 return NavigationDecision.prevent;
//               } else if (request.url.toString().contains(failedUrl)) {
//                 await Provider.of<PlaceOrderService>(context, listen: false)
//                     .doNext(context, 'failed', paymentFailed: true);
//                 return NavigationDecision.prevent;
//               } else {
//                 return NavigationDecision.navigate;
//               }
//             },
//           )),
//     );
//   }

//   Future<bool> verifyPayment(String url) async {
//     final uri = Uri.parse(url);
//     final response = await http.get(uri);
//     print(response.body.contains('successful'));
//     return response.body.contains('successful');
//   }

//   String _getHmacHashInBase64FromString(String key, String data) {
//     final keyBytes = const Utf8Encoder().convert(key);
//     final dataBytes = const Utf8Encoder().convert(data);

//     final hmacBytes = Hmac(sha256, keyBytes).convert(dataBytes);

//
//     return hmacBytes.toString();
//   }
// }
