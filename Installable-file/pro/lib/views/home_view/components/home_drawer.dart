import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:provider/provider.dart';

import '../../../services/theme_service.dart';
import '../../../view_models/sign_in_view_model/sign_in_view_model.dart';
import '../../menu_view/components/menu_tiles.dart';
import '../../menu_view/components/menu_user_order_data.dart';
import '../../menu_view/components/menu_user_tile.dart';

class HomeDrawer extends StatelessWidget {
  const HomeDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    final sim = SignInViewModel.instance;
    return SizedBox(
      width: context.width / 1.3,
      child: ValueListenableBuilder(
          valueListenable: sim.li,
          builder: (context, value, child) {
            return Scaffold(
              body: Consumer<ProfileInfoService>(builder: (context, pi, child) {
                return Consumer<ThemeService>(builder: (context, ts, child) {
                  return CustomRefreshIndicator(
                    onRefresh: () async {
                      await pi.fetchProfileInfo();
                    },
                    child: Column(
                      children: [
                        Container(
                            height: context.viewPaddingTop,
                            color: context.color.accentContrastColor),
                        Expanded(
                          child: SingleChildScrollView(
                            padding: 8.paddingV,
                            physics: const AlwaysScrollableScrollPhysics(),
                            child: Column(
                              children: [
                                if (pi.singedIn) ...[
                                  MenuUserTile(ts: ts),
                                  MenuUserOrderData(ts: ts),
                                  8.toHeight,
                                ],
                                MenuTiles(
                                  ts: ts,
                                  logedIn: pi.singedIn,
                                ),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  );
                });
              }),
            );
          }),
    );
  }
}
