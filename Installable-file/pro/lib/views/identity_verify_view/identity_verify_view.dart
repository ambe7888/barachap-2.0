import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/iv_manage_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:provider/provider.dart';

import 'components/identity_not_verified.dart';
import 'components/identity_verify_button.dart';
import 'components/identity_verify_tile.dart';

class IdentityVerifyView extends StatelessWidget {
  const IdentityVerifyView({super.key});

  @override
  Widget build(BuildContext context) {
    final ivProvider = Provider.of<IvManageService>(context, listen: false);
    return Scaffold(
      backgroundColor: context.color.cardFillColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.identityVerification),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {},
        child: SingleChildScrollView(
          child: CustomFutureWidget(
            function: ivProvider.fetchIVInfo(),
            shimmer: const CustomPreloader(),
            child: Consumer<IvManageService>(builder: (context, iv, child) {
              return Column(
                children: [
                  Divider(
                    height: 8,
                    thickness: 8,
                    color: context.color.backgroundColor,
                  ),
                  if ((iv.ivInfoModel.userVerifyInfo?.status ?? "2") != "2")
                    IdentityVerifyTile(
                        verifyStatus:
                            iv.ivInfoModel.userVerifyInfo?.status == "1"),
                  if ((iv.ivInfoModel.userVerifyInfo?.status ?? "2") == "2")
                    const IdentityNotVerified(),
                ],
              );
            }),
          ),
        ),
      ),
      bottomNavigationBar:
          Consumer<IvManageService>(builder: (context, iv, child) {
        if (iv.ivInfoModel.userVerifyInfo?.status == "1") {
          return const SizedBox();
        }
        return const IdentityVerifyButton();
      }),
    );
  }
}
