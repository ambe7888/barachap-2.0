import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/constant_helper.dart';

class ThemeService with ChangeNotifier {
  get selectedTheme => darkTheme ? darkColors : lightColors;
  bool get darkTheme {
    final theme = sPref?.getString("theme");
    switch (theme) {
      case "dark":
        return true;
      case "light":
        return false;
      default:
        return ThemeMode.system == ThemeMode.dark;
    }
  }

  changeTheme(value) {
    if (value) {
      sPref?.setString("theme", "dark");
    } else {
      sPref?.setString("theme", "light");
    }
    notifyListeners();
  }
}
