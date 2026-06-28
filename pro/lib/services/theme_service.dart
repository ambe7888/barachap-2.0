import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/constant_helper.dart';

import '../models/color_model.dart';

class ThemeService with ChangeNotifier {
  ColorModel get selectedTheme => darkTheme ? darkColors : lightColors;
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

  refresh() {
    notifyListeners();
  }
}
