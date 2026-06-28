import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/service/service_details_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_network_image.dart';

class SubOrderStaffs extends StatelessWidget {
  final Staff staff;
  const SubOrderStaffs({
    super.key,
    required this.staff,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.preferredStaffs,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                12.toHeight,
                Row(
                  children: [
                    CustomNetworkImage(
                      height: 44,
                      width: 44,
                      radius: 22,
                      fit: BoxFit.cover,
                      imageUrl: staff.image,
                      name: staff.fullname,
                      userPreloader: true,
                    ),
                    6.toWidth,
                    Expanded(
                        flex: 1,
                        child: Text(
                          staff.fullname ?? "",
                          style: context.titleSmall?.bold,
                        ))
                  ],
                )
              ],
            ),
          ),
        ],
      ),
    );
  }
}
