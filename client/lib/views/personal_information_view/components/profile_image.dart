import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:provider/provider.dart';
import 'package:prohandy_client/customizations/colors.dart';
import '../../../services/profile_services/profile_info_service.dart';
import '../../../view_models/profile_edit_view_model/profile_edit_view_model.dart';
import '../profile_image_change_view.dart';

class ProfileImage extends StatelessWidget {
  const ProfileImage({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ProfileInfoService>(builder: (context, pi, child) {
      return Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
        color: context.color.accentContrastColor,
        child: Column(
          children: [
            Stack(
              alignment: Alignment.bottomRight,
              children: [
                Container(
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(
                      color: primaryColor,
                      width: 3,
                    ),
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(4.0),
                    child: CustomNetworkImage(
                      height: 120,
                      width: 120,
                      radius: 60,
                      name: pi.profileInfoModel.userDetails?.firstName,
                      fit: BoxFit.cover,
                      imageUrl: pi.profileInfoModel.userDetails?.image,
                      userPreloader: true,
                    ),
                  ),
                ),
                GestureDetector(
                  onTap: () {
                    ProfileEditViewModel.dispose;
                    ProfileEditViewModel.instance
                        .initProfile(pi.profileInfoModel.userDetails!);
                    context.toPage(const ProfileImageChangeView());
                  },
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: primaryColor,
                      shape: BoxShape.circle,
                      border: Border.all(color: Colors.white, width: 2),
                    ),
                    child: const Icon(
                      Icons.camera_alt,
                      color: Colors.white,
                      size: 20,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Text(
              "${pi.profileInfoModel.userDetails?.firstName ?? ''} ${pi.profileInfoModel.userDetails?.lastName ?? ''}",
              style: context.titleMedium?.copyWith(
                fontWeight: FontWeight.bold,
                fontSize: 20,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              pi.profileInfoModel.userDetails?.email ?? '',
              style: context.bodyMedium?.copyWith(
                color: Colors.grey,
              ),
            ),
          ],
        ),
      );
    });
  }
}
