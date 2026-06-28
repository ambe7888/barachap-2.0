import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/models/staff_models/staff_list_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_network_image.dart';

class OrderDetailsStaffs extends StatelessWidget {
  final Staff? staff;
  const OrderDetailsStaffs({
    super.key,
    this.staff,
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
                  LocalKeys.preferredStaff,
                  style: context.bodySmall,
                ),
                12.toHeight,
                Row(
                  children: [
                    CustomNetworkImage(
                      height: 44,
                      width: 44,
                      radius: 22,
                      fit: BoxFit.cover,
                      imageUrl: staff?.image,
                      name: staff?.fullname,
                      userPreloader: true,
                    ),
                    6.toWidth,
                    Expanded(
                        flex: 1,
                        child: Text(
                          staff?.fullname ?? "",
                          style: context.titleSmall?.bold.copyWith(
                              color: context.color.primaryContrastColor),
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
