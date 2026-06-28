import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/notifications.dart';
import 'package:provider/provider.dart';

import '../../../services/profile_services/profile_info_service.dart';
import '../../../services/theme_service.dart';
import '../../../view_models/home_view_model/home_view_model.dart';
import 'app_bar_cart.dart';

class HomeAppBar extends StatelessWidget {
  const HomeAppBar({super.key});

  @override
  Widget build(BuildContext context) {
    final hm = HomeViewModel.instance;
    hm.scrollController.addListener(() {
      hm.appBarSize.value = hm.scrollController.offset;
    });
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
      child: Consumer<ProfileInfoService>(builder: (context, pi, child) {
        return Consumer<ThemeService>(builder: (context, ts, child) {
          return ValueListenableBuilder(
            valueListenable: hm.appBarSize,
            builder: (context, value, child) {
              final color = value > 100
                  ? context.color.secondaryContrastColor
                  : context.color.accentContrastColor;
              return AppBar(
                leading: Padding(
                  padding: const EdgeInsets.all(2.0),
                  child: Image.asset(
                    "assets/images/app_icon.png",
                    fit: BoxFit.contain,
                  ),
                ),
                systemOverlayStyle: ts.darkTheme
                    ? SystemUiOverlayStyle.light
                    : SystemUiOverlayStyle.dark,
                leadingWidth: 50,
                backgroundColor: Colors.transparent,
                surfaceTintColor: Colors.transparent,
                title: RichText(
                    text: TextSpan(
                        text: LocalKeys.hello,
                        style: context.titleLarge?.bold.copyWith(color: color),
                        children: [
                      if (pi.profileInfoModel.userDetails?.firstName != null)
                        TextSpan(
                            text: pi.profileInfoModel.userDetails?.firstName ==
                                    null
                                ? null
                                : ", ${pi.profileInfoModel.userDetails?.firstName}")
                    ])),
                actions: [
                  const Notifications(),
                  12.toWidth,
                  const AppBarCart(),
                  8.toWidth,
                ],
              );
            },
          );
        });
      }),
    );
  }
}
