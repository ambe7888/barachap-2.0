import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/views/service_provider_view/components/staff_tile.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/service/service_details_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsStaffs extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceDetailsStaffs({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    return ((serviceDetails.allServices?.provider?.staffs ??
                    serviceDetails.allServices?.admin?.staffs) ??
                [])
            .isEmpty
        ? EmptyElement(text: LocalKeys.noStaffAdded)
        : Wrap(
            runSpacing: 12,
            children: ((serviceDetails.allServices?.provider?.staffs ??
                        serviceDetails.allServices?.admin?.staffs) ??
                    [])
                .map((staff) {
              return SquircleContainer(
                radius: 8,
                width: double.infinity,
                borderColor: context.color.primaryBorderColor,
                child: StaffTile(
                  name: staff.fullname ?? staff.email?.obscureEmail ?? "---",
                  createdAt: DateTime.now(),
                  imageUrl: staff.image,
                ),
              );
            }).toList(),
          );
  }
}
