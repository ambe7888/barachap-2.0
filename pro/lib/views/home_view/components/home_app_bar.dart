import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/notifications.dart';
import 'package:provider/provider.dart';

class HomeAppBar extends StatelessWidget {
  const HomeAppBar({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, pi, child) {
      return Consumer<ProfileInfoService>(builder: (context, pi, child) {
        return Row(
          children: [
            Expanded(
              flex: 1,
              child: Text(
                "${LocalKeys.hello}, ${pi.profileInfoModel.userDetails?.firstName}",
                style: context.titleLarge?.bold,
              ),
            ),
            12.toHeight,
            const Notifications(),
            12.toHeight,
          ],
        );
      });
    });
  }
}
