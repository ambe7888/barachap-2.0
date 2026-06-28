import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:prohand/views/service_provider_view/components/staff_tile.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsStaffs extends StatelessWidget {
  const ServiceDetailsStaffs({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final serviceDetails = sd.serviceDetailsModel;
      return (serviceDetails.allServices?.provider?.staffs ?? []).isEmpty
          ? SizedBox(
              width: double.infinity,
              height: 200,
              child: EmptyElement(text: LocalKeys.noStaffAllocated))
          : Wrap(
              runSpacing: 12,
              children: (serviceDetails.allServices?.provider?.staffs ?? [])
                  .map((staff) {
                return SquircleContainer(
                  radius: 8,
                  width: double.infinity,
                  borderColor: context.color.primaryBorderColor,
                  child: StaffTile(
                    name: staff.fullname ?? "---",
                    createdAt: DateTime.now(),
                    imageUrl: staff.image,
                  ),
                );
              }).toList(),
            );
    });
  }
}
