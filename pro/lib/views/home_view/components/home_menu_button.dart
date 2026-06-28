import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:provider/provider.dart';

import '../../../helper/svg_assets.dart';
import '../../../services/theme_service.dart';
import '../../../view_models/home_view_model/home_view_model.dart';

class HomeMenuButton extends StatelessWidget {
  const HomeMenuButton({super.key});

  @override
  Widget build(BuildContext context) {
    final hm = HomeViewModel.instance;
    return Consumer<ThemeService>(builder: (context, pi, child) {
      return GestureDetector(
        onTap: () {
          hm.scaffoldKey.currentState?.openDrawer();
        },
        child: Container(
          color: Colors.transparent,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              SvgAssets.menu.toSVGSized(
                24,
                color: context.color.secondaryContrastColor,
              ),
            ],
          ),
        ),
      );
    });
  }
}
