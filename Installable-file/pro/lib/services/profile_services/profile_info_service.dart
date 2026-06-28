import 'dart:convert';
import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/profile_models/profile_info_model.dart';
import 'package:prohand/services/push_notification_service.dart';

import '../../data/network/network_api_services.dart';

class ProfileInfoService with ChangeNotifier {
  ProfileInfoModel? _profileInfoModel;
  ProfileInfoModel get profileInfoModel =>
      _profileInfoModel ?? ProfileInfoModel();

  bool get singedIn => _profileInfoModel?.userDetails != null;

  setSingedIn() {}

  fetchProfileInfo({trySkip = false}) async {
    var url = AppUrls.profileInfoUrl;
    final localProfile = sPref?.getString("profile");
    debugPrint(getToken.toString());
    if (localProfile != null && trySkip) {
      _profileInfoModel = ProfileInfoModel.fromJson(
          jsonDecode(sPref!.getString("profile") ?? "{}"));
      notifyListeners();
    }
    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.profile, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      _profileInfoModel = ProfileInfoModel.fromJson(responseData);
      sPref?.setString(
          "profile", jsonEncode(_profileInfoModel?.toJson() ?? {}));
      if (profileInfoModel.userDetails?.firebaseToken !=
          sPref?.getString("device_token")) {
        PushNotificationService().updateDeviceToken(forceUpdate: true);
      }
      log(jsonEncode(responseData));
      notifyListeners();
      return _profileInfoModel?.userDetails != null;
    } else {}
  }

  void reset() {
    _profileInfoModel = null;
    sPref?.remove("profile");
    notifyListeners();
  }
}
