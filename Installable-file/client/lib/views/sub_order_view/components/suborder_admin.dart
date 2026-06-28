import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/models/service/service_details_model.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:provider/provider.dart';

import '../../../services/profile_services/profile_info_service.dart';

class SuborderAdmin extends StatelessWidget {
  final AdminModel admin;

  const SuborderAdmin({super.key, required this.admin});

  @override
  Widget build(BuildContext context) {
    final myDetails = Provider.of<ProfileInfoService>(context, listen: false)
        .profileInfoModel
        .userDetails!;
    return GestureDetector(
      child: Container(
        color: context.color.accentContrastColor,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomNetworkImage(
              height: 48,
              width: 48,
              radius: 24,
              imageUrl: admin.image,
              fit: BoxFit.cover,
              name: admin.name,
              userPreloader: true,
            ),
            8.toWidth,
            Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text(
                          admin.name ?? "---",
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: context.titleMedium?.bold,
                        ),
                        4.toWidth,
                        SvgAssets.verified.toSVGSized(20,
                            color: context.color.primarySuccessColor),
                      ],
                    ),
                    4.toHeight,
                    RichText(
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      text: TextSpan(
                        text: LocalKeys.officialPlatformProvider,
                        style: context.bodyMedium?.copyWith(
                            color: context.color.secondaryContrastColor),
                      ),
                    ),
                  ],
                )),
          ],
        ),
      ),
    );
  }
}
