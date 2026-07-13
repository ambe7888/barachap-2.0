import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class IntroService with ChangeNotifier {
  final introData = [
    {
      "title": "Trouvez des Missions",
      "description":
          "Consultez et trouvez des milliers de missions proposées par les clients et obtenez des commandes.",
      "image": "assets/images/intro_1.png",
    },
    {
      "title": "Postulez aux Demandes",
      "description":
          "Répondez aux besoins spécifiques des clients en proposant vos services.",
      "image": "assets/images/intro_2.png",
    },
    {
      "title": "Chat en Direct",
      "description":
          "Discutez directement avec les clients pour collaborer efficacement.",
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
