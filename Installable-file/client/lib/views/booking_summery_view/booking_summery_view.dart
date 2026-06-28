import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/booking_summery_view/components/booking_summary_address.dart';
import 'package:prohandy_client/views/booking_summery_view/components/booking_summery_buttons.dart';
import 'package:prohandy_client/views/booking_summery_view/components/booking_summery_service_tile.dart';

import '../../view_models/service_booking_view_model/service_booking_view_model.dart';
import 'components/booking_summary_cost_info.dart';
import 'components/booking_summary_order_note.dart';
import 'components/booking_summery_date_schedule.dart';
import 'components/booking_summery_staff.dart';
import 'components/update_remove_from_cart_buttons.dart';

class BookingSummeryView extends StatelessWidget {
  const BookingSummeryView({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.summary),
      ),
      body: Scrollbar(
          child: SingleChildScrollView(
        padding: 8.paddingV,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const BookingSummeryServiceTile(),
            8.toHeight,
            BookingSummeryDateSchedule(
                date: svm.selectedDate, schedule: svm.selectedSchedule),
            8.toHeight,
            BookingSummaryAddress(
              address: svm.selectedAddress,
            ),
            8.toHeight,
            const BookingSummaryOrderNote(),
            8.toHeight,
            if (svm.selectedStaff.value != null) ...[
              const BookingSummeryStaff(),
              8.toHeight
            ],
            const BookingSummeryCostInfo(),
          ],
        ),
      )),
      bottomNavigationBar: svm.fromCart
          ? const UpdateRemoveFromCartButtons()
          : const BookingSummeryButtons(),
    );
  }
}
