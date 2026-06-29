import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:prohandy_client/helper/local_keys.g.dart';

class IntroService with ChangeNotifier {
  List<Map<String, dynamic>> get introData => [
    {
      "title": LocalKeys.welcomeToProhandy,
      "description": LocalKeys.bookExpertHandymen,
      "image": "assets/images/intro_1_v2.png",
    },
    {
      "title": LocalKeys.bookFromServices,
      "description": LocalKeys.bookYoursFromAWideRange,
      "image": "assets/images/intro_2.png",
    },
    {
      "title": LocalKeys.postJobs,
      "description": LocalKeys.didntFindWhatYoureLookingFor,
      "image": "assets/images/intro_3.png",
    },
  ];
  int currentIndex = 0;

  void setIndex(int index) {
    currentIndex = index;
    notifyListeners();
  }

  checkIntro() async {
    final sp = await SharedPreferences.getInstance();
    bool? intro = sp.getBool("intro");
    if (intro == null) {
      return true;
    }
    return false;
  }

  seeIntroValue() async {
    final sp = await SharedPreferences.getInstance();
    sp.setBool("intro", true);
  }

  fetchIntro(BuildContext context) {}
}
