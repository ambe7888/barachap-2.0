import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/view_models/sign_in_view_model/sign_in_view_model.dart';
import 'package:prohand/views/menu_view/components/menu_tiles.dart';
import 'package:prohand/views/menu_view/components/menu_user_order_data.dart';
import 'package:prohand/views/menu_view/components/menu_user_tile.dart';
import 'package:provider/provider.dart';

import '../../services/theme_service.dart';

class MenuView extends StatelessWidget {
  const MenuView({super.key});

  @override
  Widget build(BuildContext context) {
    final sim = SignInViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: sim.li,
      builder: (context, value, child) {
        return Scaffold(
          body: Consumer<ThemeService>(builder: (context, ts, child) {
            return SingleChildScrollView(
              padding: 16.paddingV,
              child: Column(
                children: [
                  if (value) ...[
                    MenuUserTile(ts: ts),
                    MenuUserOrderData(ts: ts),
                    8.toHeight
                  ],
                  MenuTiles(
                    ts: ts,
                    logedIn: value,
                  ),
                ],
              ),
            );
          }),
        );
      },
    );
  }
}
