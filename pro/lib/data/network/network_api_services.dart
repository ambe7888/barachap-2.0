import 'dart:convert';
import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:prohand/services/profile_services/profile_info_service.dart';

import '../../data/network/base_api_services.dart';
import '../../helper/constant_helper.dart';
import '../../helper/extension/string_extension.dart';
import '../../helper/local_keys.g.dart';
import '../../helper/network_connectivity.dart';
import '../../main.dart';
import '../../view_models/sign_in_view_model/sign_in_view_model.dart';
import '../../views/sign_in_view/sign_in_view.dart';
import '../app_exceptions.dart';

class NetworkApiServices extends BaseApiServices {
  final NetworkConnectivity networkConnectivity = NetworkConnectivity.instance;
  @override
  Future<Map?> getApi(String url, requestName,
      {headers, timeoutSeconds}) async {
    if (kDebugMode) {
      debugPrint(url);
    }

    final hasConnection = await networkConnectivity.currentStatus();
    if (!hasConnection) {
      debugPrint("connection state is $hasConnection".toString());

      LocalKeys.noConnectionFound.showToast();
      return null;
    }
    Map<String, String> h = headers ?? {};
    h.addAll({
      'Accept': 'application/json',
    });
    Map? responseJson;
    try {
      final response = await http
          .get(Uri.parse(url), headers: h)
          .timeout(Duration(seconds: timeoutSeconds ?? 20));
      if (kDebugMode) {
        debugPrint(response.body.toString());
        debugPrint(response.statusCode.toString());
      }
      responseJson = returnResponse(response);
    } on SocketException {
      InternetException('');
      debugPrint("invalid url");
      showError(requestName, LocalKeys.invalidUrl);
    } on RequestTimeOut {
      debugPrint("request timeout");
      showError(requestName, LocalKeys.invalidUrl);
      RequestTimeOut('');
    } catch (e) {
      debugPrint(e.toString());
      showError(requestName, e.toString());
    }
    debugPrint(responseJson?.toString());
    return responseJson;
  }

  @override
  Future<Map?> postApi(var data, String url, requestName,
      {headers, timeoutSeconds}) async {
    if (kDebugMode) {
      debugPrint(url);
      debugPrint(data.toString());
    }
    final hasConnection = await networkConnectivity.currentStatus();
    if (!hasConnection) {
      debugPrint("connection state is $hasConnection".toString());

      LocalKeys.noConnectionFound.showToast();
      return null;
    }
    Map<String, String> h = headers ?? {};
    h.putIfAbsent('Accept', () => 'application/json');
    Map? responseJson;
    try {
      final response = await http
          .post(Uri.parse(url), body: data, headers: h)
          .timeout(Duration(seconds: timeoutSeconds ?? 20));
      if (kDebugMode) {
        debugPrint(response.body.toString());
        debugPrint(response.statusCode.toString());
      }
      responseJson = returnResponse(response);
    } on SocketException {
      debugPrint("invalid url");
      showError(requestName, LocalKeys.invalidUrl);
    } on RequestTimeOut {
      debugPrint("request timeout");
      showError(requestName, LocalKeys.requestTimeOut);
    } catch (e) {
      showError(requestName, e.toString());
      debugPrint(e.toString());
    }
    return responseJson;
  }

  Future<Map?> postWithFileApi(http.MultipartRequest request, requestName,
      {headers}) async {
    if (kDebugMode) {
      debugPrint(request.url.toString());
      debugPrint(request.fields.toString());
    }
    final hasConnection = await networkConnectivity.currentStatus();
    if (!hasConnection) {
      debugPrint("connection state is $hasConnection".toString());

      LocalKeys.noConnectionFound.showToast();
      return null;
    }

    Map? responseJson;
    try {
      final responseStream = await request.send();
      final data = await responseStream.stream.toBytes();
      http.Response response =
          http.Response.bytes(data, responseStream.statusCode);
      if (kDebugMode) {
        debugPrint(response.body.toString());
        debugPrint(responseStream.statusCode.toString());
      }
      responseJson = returnResponse(response);
    } on SocketException {
      debugPrint("invalid url");
      showError(requestName, LocalKeys.invalidUrl);
    } on RequestTimeOut {
      debugPrint("request timeout");
      showError(requestName, LocalKeys.requestTimeOut);
    } catch (e) {
      showError(requestName, e.toString());
      debugPrint(e.toString());
    }
    return responseJson;
  }

  dynamic returnResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode <= 299) {
      try {
        dynamic responseJson = jsonDecode(response.body);

        return responseJson;
      } catch (_) {
        return {};
      }
    } else if (response.statusCode >= 300 && response.statusCode <= 399) {
      try {
        dynamic responseJson = jsonDecode(response.body);
        if ((responseJson["message"] ?? responseJson["msg"]) != null) {
          (responseJson["message"] ?? responseJson["msg"])
              .toString()
              .showToast();
          return null;
        }
      } catch (_) {
        debugPrint(response.body.toString());
        throw FetchDataException('${response.reasonPhrase}');
      }
    }
    switch (response.statusCode) {
      case 400:
        try {
          dynamic responseJson = jsonDecode(response.body);
          if ((responseJson["message"] ?? responseJson["msg"]) != null) {
            (responseJson["message"] ?? responseJson["msg"])
                .toString()
                .showToast();
            return null;
          }
        } catch (_) {
          debugPrint(response.body.toString());
          throw FetchDataException('${response.reasonPhrase}');
        }
      case 422:
        try {
          checkAuthentication(response);
          dynamic responseJson = jsonDecode(response.body);

          showValidationErrors(responseJson);
        } catch (error) {
          if (error is String) {
            rethrow;
          }
          debugPrint(response.body.toString());
          throw FetchDataException('${response.reasonPhrase}');
        }
      case 401:
        try {
          checkAuthentication(response);
          dynamic responseJson = jsonDecode(response.body);
          showValidationErrors(responseJson);
        } catch (_) {
          if (_ is String) {
            rethrow;
          }
          debugPrint(response.body.toString());
          throw FetchDataException('${response.reasonPhrase}');
        }

      default:
        try {
          checkAuthentication(response);
          dynamic responseJson = jsonDecode(response.body);

          showValidationErrors(responseJson);
        } catch (_) {
          if (_ is String) {
            rethrow;
          }
          debugPrint(response.body.toString());
          throw FetchDataException('${response.reasonPhrase}');
        }
    }
  }

  checkAuthentication(http.Response response) {
    if (response.body.contains("Unauthenticated.")) {
      debugPrint(navigatorKey.currentState?.toString());
      ProfileInfoService().reset();
      SignInViewModel.instance.initSavedInfo();
      navigatorKey.currentState?.pushAndRemoveUntil(
          MaterialPageRoute(
            builder: (context) => const SignInView(),
          ),
          (route) => false);
      LocalKeys.sessionExpired.showToast();
      sPref?.remove("profile");
      setToken(null);
      return null;
    }
  }

  showError(requestName, error) {
    if (requestName != null) {
      "$requestName: $error".showToast();
    }
  }

  showValidationErrors(Map json) {
    debugPrint("in validation error method".toString());
    if (!json.containsKey("errors") || json["errors"] == {}) {
      debugPrint(json["message"].toString());
      throw json["message"] ?? json["msg"];
    }

    final errors = json["errors"];
    debugPrint(errors.toString());
    if (errors.isEmpty) {
      debugPrint("throwing error".toString());
      throw json["message"] ?? json;
    }
    if (errors is Map) {
      final keys = errors.keys.toList();
      keys.remove("status");
      debugPrint(keys.last.toString());
      final passwordError = errors[keys.first];
      debugPrint(passwordError.toString());
      if (passwordError is String) {
        throw passwordError;
      } else if (passwordError is List && passwordError.isNotEmpty) {
        throw passwordError.first;
      }
    }
  }
}
