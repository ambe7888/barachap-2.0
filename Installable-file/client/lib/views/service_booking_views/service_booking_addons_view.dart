import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/home_services/service_details_service.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/service_booking_views/components/service_booking_address.dart';
import 'package:prohandy_client/views/service_booking_views/components/service_booking_date.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_button.dart';
import '../../view_models/service_booking_view_model/service_booking_view_model.dart';
import '../booking_summery_view/booking_summery_view.dart';
import 'components/service_booking_addons.dart';
import 'components/service_booking_staffs.dart';

class ServiceBookingAddonsView extends StatelessWidget {
  const ServiceBookingAddonsView({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    final serviceDetails =
        Provider.of<ServiceDetailsService>(context, listen: false)
            .serviceDetailsModel(svm.selectedService.value?.id);
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.serviceAddonsAndDate),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Divider(
              thickness: 8,
              height: 8,
              color: context.color.backgroundColor,
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  ServiceBookingAddons(
                    serviceDetails: serviceDetails,
                  ),
                  16.toHeight,
                  ServiceBookingStaffs(),
                  16.toHeight,
                  const ServiceBookingDate(),
                  16.toHeight,
                  const ServiceBookingAddress(),
                  16.toHeight,
                  FieldWithLabel(
                    label: LocalKeys.addNote,
                    hintText: LocalKeys.bookingNoteExmp,
                    controller: svm.descriptionController,
                    minLines: 5,
                    textInputAction: TextInputAction.newline,
                  ),
                ],
              ),
            )
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: CustomButton(
            onPressed: () {
              if (!svm.serviceBookingAddonViewValidate) return;
              svm.setAddons(context);
              context.animateToPage(const BookingSummeryView());
            },
            btText: LocalKeys.continueO),
      ),
    );
  }
}
