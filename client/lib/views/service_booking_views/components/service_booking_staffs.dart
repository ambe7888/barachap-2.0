import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/field_label.dart';
import 'package:provider/provider.dart';

import '../../../services/home_services/service_details_service.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';
import 'selectable_staff_avatar.dart';

class ServiceBookingStaffs extends StatelessWidget {
  const ServiceBookingStaffs({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;

    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final serviceDetails =
          sd.serviceDetailsModel(svm.selectedService.value?.id);
      final staffs = serviceDetails.allServices?.provider?.staffs ??
          serviceDetails.allServices?.admin?.staffs ??
          [];
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          FieldLabel(label: LocalKeys.staffs),
          if (staffs.isEmpty)
            Text(LocalKeys.noStaffAdded, style: context.bodyMedium),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Wrap(
              spacing: 12,
              children: staffs
                  .map((staff) => SelectableStaffAvatar(
                      id: staff.id,
                      name: staff.fullname ?? "---",
                      imageUrl: staff.image,
                      onSelect: () {
                        svm.selectedStaff.value = staff;
                      },
                      valueListenable: svm.selectedStaff))
                  .toList(),
            ),
          )
        ],
      );
    });
  }
}
