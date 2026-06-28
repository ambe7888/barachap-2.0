import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/home_view/components/staff_assigned_order_sheet.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/order_services/todays_order_service.dart';
import '../../../services/theme_service.dart';
import '../../../utils/components/custom_network_image.dart';

class HomeAssignedStaffs extends StatelessWidget {
  const HomeAssignedStaffs({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<TodaysOrdersService>(builder: (context, ts, child) {
        return Container(
          padding: 16.paddingV,
          width: double.infinity,
          color: context.color.accentContrastColor,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(LocalKeys.assignedStaffs, style: context.headlineLarge?.bold)
                  .hp20,
              12.toHeight,
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                padding: 24.paddingH,
                child: Wrap(
                  spacing: 12,
                  children: ts.staffs.map((staff) {
                    return GestureDetector(
                      onTap: () {
                        showModalBottomSheet(
                          context: context,
                          isScrollControlled: true,
                          backgroundColor: context.color.cardFillColor,
                          builder: (context) {
                            return StaffAssignedOrderSheet(
                              staff: staff,
                            );
                          },
                        );
                      },
                      child: CustomNetworkImage(
                        height: 44,
                        width: 44,
                        radius: 22,
                        fit: BoxFit.cover,
                        imageUrl: staff.image,
                        name: staff.fullname,
                        userPreloader: true,
                      ),
                    );
                  }).toList(),
                ),
              )
            ],
          ),
        );
      });
    });
  }
}
