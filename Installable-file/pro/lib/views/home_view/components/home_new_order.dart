import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../services/profile_services/dashboard_info_service.dart';
import '../../../services/theme_service.dart';

class HomeNewOrder extends StatelessWidget {
  const HomeNewOrder({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<DashboardInfoService>(builder: (context, di, child) {
        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
          color: context.color.accentContrastColor,
          child: di.isLoading
              ? SquircleContainer(
                  radius: 10,
                  color: mutedPrimaryColor,
                  padding: 12.paddingAll,
                  child: const Row(
                    children: [
                      SizedBox(
                        height: 44,
                      )
                    ],
                  ),
                ).shim
              : SquircleContainer(
                  radius: 10,
                  color: mutedPrimaryColor,
                  padding: 12.paddingAll,
                  child: Row(
                    children: [
                      Expanded(
                        flex: 1,
                        child: Text(
                          "1 ${LocalKeys.newOrdersWaiting}",
                          style: context.titleSmall?.bold
                              .copyWith(color: primaryColor),
                        ),
                      ),
                      ElevatedButton(
                        onPressed: () {},
                        child: Text(LocalKeys.seeDetails),
                      )
                    ],
                  ),
                ),
        );
      });
    });
  }
}
