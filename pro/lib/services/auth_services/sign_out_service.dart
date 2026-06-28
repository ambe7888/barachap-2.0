import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../data/network/network_api_services.dart';
import '../../helper/app_urls.dart';
import '../../helper/local_keys.g.dart';

class SignOutService {
  trySignOut() async {
    final responseData = await NetworkApiServices().postApi(
      {},
      AppUrls.signOutUrl,
      LocalKeys.signOut,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      LocalKeys.signOutSuccessful.showToast();
      sPref?.remove("profile");
      return true;
    }
  }
}
