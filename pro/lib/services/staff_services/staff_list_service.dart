import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../models/staff_models/staff_list_model.dart';

class StaffListService with ChangeNotifier {
  StaffListModel? _staffListModel;
  StaffListModel get staffListModel => _staffListModel ?? StaffListModel();

  String token = "";
  var nextPage;

  bool get shouldAutoFetch => _staffListModel == null || token.isInvalid;

  fetchStaffList() async {
    var url = AppUrls.staffListUrl;
    token = getToken;

    final responseData = await NetworkApiServices()
        .getApi(url, LocalKeys.staffs, headers: acceptJsonAuthHeader);

    if (responseData != null) {
      final tempData = StaffListModel.fromJson(responseData);

      nextPage = tempData.pagination?.nextPageUrl;
      _staffListModel = tempData;
    } else {
      _staffListModel ??= StaffListModel();
    }
    notifyListeners();
  }

  void removeStaff(id) {
    _staffListModel?.allStaffs
        ?.removeWhere((staff) => staff.id.toString() == id.toString());
    notifyListeners();
  }
}
