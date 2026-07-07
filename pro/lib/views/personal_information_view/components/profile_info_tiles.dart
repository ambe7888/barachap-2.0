import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';
import 'package:prohand/views/identity_verify_view/identity_verify_view.dart';
import 'package:prohand/views/profile_info_edit_view/profile_info_edit_view.dart';
import 'package:prohand/views/service_area_view/service_area_view.dart';
import 'package:provider/provider.dart';

import '../../../helper/svg_assets.dart';
import '../../../view_models/change_email_phone_view_model/change_email_phone_view_model.dart';
import '../../change_email_view/change_email_view.dart';
import '../../change_password_view/change_password_view.dart';
import '../../change_phone_view/change_phone_view.dart';
import '../../menu_view/components/menu_tile.dart';
import '../../user_service_type_view/user_service_type_view.dart';

class ProfileInfoTiles extends StatelessWidget {
  const ProfileInfoTiles({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final pi = Provider.of<ProfileInfoService>(context, listen: false);
    return Container(
      color: context.color.accentContrastColor,
      child: Column(
        children: [
          MenuTile(
            title: LocalKeys.editInformation,
            svg: SvgAssets.user,
            onPress: () {
              ProfileEditViewModel.dispose;
              ProfileEditViewModel.instance
                ..initProfile(pi.profileInfoModel.userDetails!)
                ..initStoreImages(pi.profileInfoModel.userDetails!);
              context.toPage(const ProfileInfoEditView());
            },
            haveDivider: true,
          ),
          MenuTile(
            title: LocalKeys.identityVerification,
            svg: SvgAssets.id,
            onPress: () {
              context.toPage(const IdentityVerifyView());
            },
            haveDivider: true,
          ),
          MenuTile(
            title: LocalKeys.changeEmail,
            svg: SvgAssets.email,
            onPress: () {
              ChangeEmailPhoneViewModel.dispose;
              context.toPage(const ChangeEmailView());
            },
            haveDivider: true,
          ),
          MenuTile(
            title: LocalKeys.changePhone,
            svg: SvgAssets.phone,
            onPress: () {
              ChangeEmailPhoneViewModel.dispose;
              if (pi.profileInfoModel.userDetails?.phone != null) {
                String fullPhone = pi.profileInfoModel.userDetails!.phone!;
                if (fullPhone.startsWith('+225')) {
                  fullPhone = fullPhone.substring(4);
                } else if (fullPhone.startsWith('225')) {
                  fullPhone = fullPhone.substring(3);
                }
                ChangeEmailPhoneViewModel.instance.phoneController.text =
                    fullPhone;
              }
              context.toPage(const ChangePhoneView());
            },
            haveDivider: true,
          ),
          MenuTile(
            title: LocalKeys.changePassword,
            svg: SvgAssets.lock,
            onPress: () {
              context.toPage(const ChangePasswordView());
            },
          ),
        ],
      ),
    );
  }
}
