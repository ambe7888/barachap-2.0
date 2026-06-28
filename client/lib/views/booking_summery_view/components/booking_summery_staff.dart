import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';

class BookingSummeryStaff extends StatelessWidget {
  const BookingSummeryStaff({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
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
                  overflow: TextOverflow.ellipsis,
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
                      imageUrl: svm.selectedStaff.value?.image,
                      name: svm.selectedStaff.value?.fullname,
                      userPreloader: true,
                    ),
                    6.toWidth,
                    Expanded(
                        flex: 1,
                        child: Text(
                          svm.selectedStaff.value?.fullname ?? "",
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
