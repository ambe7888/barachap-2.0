import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:provider/provider.dart';

import '../../services/home_services/service_details_service.dart';
import '../../utils/components/custom_button.dart';
import '../../view_models/service_booking_view_model/service_booking_view_model.dart';
import '../booking_summery_view/booking_summery_view.dart';
import '../service_booking_views/components/selectable_staff_tile.dart';

class ServiceBookingStaffView extends StatelessWidget {
  const ServiceBookingStaffView({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final serviceDetails =
          sd.serviceDetailsModel(svm.selectedService.value?.id);
      final staffs = serviceDetails.allServices?.provider?.staffs ??
          serviceDetails.allServices?.admin?.staffs ??
          [];
      return Scaffold(
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.choseStaff),
        ),
        body: Column(
          children: [
            Divider(
              height: 8,
              thickness: 8,
              color: context.color.primaryBorderColor,
            ),
            Expanded(
              child: Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                color: context.color.accentContrastColor,
                child: ListView.separated(
                    itemBuilder: (context, index) {
                      final staff = staffs[index];
                      return SelectableStaffTile(
                          id: staff.id,
                          name: staff.fullname ?? "---",
                          imageUrl: staff.image,
                          onSelect: () {
                            svm.selectedStaff.value = staff;
                          },
                          valueListenable: svm.selectedStaff);
                    },
                    separatorBuilder: (context, index) {
                      return const SizedBox().divider.hp20;
                    },
                    itemCount: staffs.length),
              ),
            )
          ],
        ),
        bottomNavigationBar: Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
              color: context.color.accentContrastColor,
              border: Border(
                  top: BorderSide(color: context.color.primaryBorderColor))),
          child: CustomButton(
              onPressed: () {
                if (svm.selectedStaff.value == null) {
                  LocalKeys.selectStaff.showToast();
                  return;
                }
                context.animateToPage(const BookingSummeryView());
              },
              btText: LocalKeys.continueO),
        ),
      );
    });
  }
}
