import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../../services/booking_services/booking_schedule_service.dart';
import '../../../utils/components/custom_future_widget.dart';
import '../../../utils/components/custom_preloader.dart';
import '../../../view_models/service_provider_view_model/service_provider_view_model.dart';

class ProviderDetailsSchedules extends StatelessWidget {
  final userDetails;
  const ProviderDetailsSchedules({super.key, this.userDetails});

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    return Consumer<BookingScheduleService>(builder: (context, bs, child) {
      return ValueListenableBuilder(
          valueListenable: spm.selectedDate,
          builder: (context, value, child) {
            if (1 == 1) {
              return FutureBuilder(
                  future:
                      bs.shouldAutoFetch(userDetails.id, spm.selectedDate.value)
                          ? bs.fetchScheduleList(
                              spm.selectedDate.value, userDetails.id)
                          : null,
                  builder: (context, snap) {
                    if (snap.connectionState == ConnectionState.waiting) {
                      return const CustomPreloader();
                    }
                    return SizedBox(
                      width: double.infinity,
                      child: Wrap(
                        spacing: 16,
                        runSpacing: 8,
                        alignment: WrapAlignment.center,
                        children: bs.scheduleListModel.schedules.map((i) {
                          if ((i.value ?? "").isEmpty) {
                            return const SizedBox();
                          }
                          return _button(
                            title: i.value ?? "",
                            onPressed: () {},
                            isSelected: false,
                          );
                        }).toList(),
                      ),
                    );
                  });
            }
            return CustomFutureWidget(
              function: bs.shouldAutoFetch(
                      userDetails.id, spm.selectedDate.value)
                  ? bs.fetchScheduleList(spm.selectedDate.value, userDetails.id)
                  : null,
              isLoading: bs.isLoading,
              shimmer: const CustomPreloader(),
              child: SizedBox(
                width: double.infinity,
                child: Wrap(
                  spacing: 16,
                  runSpacing: 8,
                  alignment: WrapAlignment.center,
                  children: bs.scheduleListModel.schedules.map((i) {
                    if ((i.value ?? "").isEmpty) {
                      return const SizedBox();
                    }
                    return _button(
                      title: i.value ?? "",
                      onPressed: () {},
                      isSelected: false,
                    );
                  }).toList(),
                ),
              ),
            );
          });
    });
  }

  Widget _button(
      {required String title,
      bool isSelected = false,
      required void Function()? onPressed}) {
    return isSelected
        ? ElevatedButton.icon(
            onPressed: () {},
            label: Text(title),
          )
        : OutlinedButton.icon(
            onPressed: onPressed,
            label: Text(title),
          );
  }
}
