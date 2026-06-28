import 'dart:math';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/home_services/service_details_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/field_label.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';
import 'package:provider/provider.dart';

import '../../../helper/constant_helper.dart';
import '../../../models/schedule_list_model.dart';
import '../../../services/booking_services/booking_schedule_service.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class ServiceBookingDate extends StatelessWidget {
  final dynamic providerId;
  final dynamic admin;
  const ServiceBookingDate({super.key, this.providerId, this.admin});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: svm.selectedDate,
      builder: (context, value, child) {
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            FieldLabel(
              label: LocalKeys.dateAndSchedule,
              isRequired: true,
            ),
            if (value == null)
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () async {
                    onTap(context);
                  },
                  label: Text(LocalKeys.selectDateAndSchedule),
                  icon: SvgAssets.calendar.toSVGSized(24,
                      color: context.color.tertiaryContrastColo),
                ),
              ),
            if (value != null)
              Row(
                children: [
                  Expanded(
                    flex: 1,
                    child: Text(
                      "${DateFormat("EEEE, dd MMMM", dProvider.languageSlug).format(value)}, ${svm.selectedSchedule.value?.value ?? "---"}",
                      style: context.titleSmall,
                    ),
                  ),
                  6.toWidth,
                  GestureDetector(
                    onTap: () {
                      onTap(context);
                    },
                    child: SquircleContainer(
                      padding: 6.paddingAll,
                      borderColor: context.color.primaryBorderColor,
                      radius: 8,
                      child: SvgAssets.pencil.toSVGSized(24,
                          color: context.color.tertiaryContrastColo),
                    ),
                  ),
                ],
              ),
          ],
        );
      },
    );
  }

  onTap(BuildContext context) async {
    final now = DateTime.now();
    final svm = ServiceBookingViewModel.instance;

    final ValueNotifier<DateTime?> selectedDate =
        ValueNotifier(svm.selectedDate.value);
    final ValueNotifier<Schedule?> selectedSchedule =
        ValueNotifier(svm.selectedSchedule.value);
    if (svm.selectedDate.value == null) {
      svm.dateScheduleType.value = SelectingScheduleType.date;
    }
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (context) {
        return SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Align(
                alignment: Alignment.center,
                child: Container(
                  height: 4,
                  width: 48,
                  margin: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(12),
                    color: context.color.mutedContrastColor,
                  ),
                ),
              ),
              ValueListenableBuilder(
                valueListenable: svm.dateScheduleType,
                builder: (context, value, child) {
                  if (value == SelectingScheduleType.schedule) {
                    return Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        GestureDetector(
                          onTap: () {
                            svm.dateScheduleType.value =
                                SelectingScheduleType.date;
                          },
                          child: SquircleContainer(
                              radius: 10,
                              padding: 8.paddingAll,
                              borderColor: context.color.primaryBorderColor,
                              child: Row(
                                children: [
                                  CircleAvatar(
                                    radius: 16,
                                    backgroundColor:
                                        context.color.mutedPendingColor,
                                    child: SvgAssets.calendar.toSVGSized(16,
                                        color:
                                            context.color.primaryPendingColor),
                                  ),
                                  6.toWidth,
                                  Expanded(
                                    flex: 1,
                                    child: Text(
                                      DateFormat("EEEE, dd MMMM")
                                          .format(selectedDate.value!),
                                      style: context.titleSmall,
                                    ),
                                  ),
                                  6.toWidth,
                                  Transform.rotate(
                                    angle: context.dProvider.textDirectionRight
                                        ? pi
                                        : 0,
                                    child: SvgAssets.chevron.toSVGSized(
                                      20,
                                      color:
                                          context.color.secondaryContrastColor,
                                    ),
                                  ),
                                ],
                              )),
                        ),
                        20.toHeight,
                        FieldLabel(label: LocalKeys.availableSlots),
                        Consumer<BookingScheduleService>(
                            builder: (context, bs, child) {
                          return CustomFutureWidget(
                            isLoading: bs.isLoading,
                            shimmer: const CustomPreloader(),
                            child: SizedBox(
                              width: double.infinity,
                              child: Wrap(
                                spacing: 16,
                                runSpacing: 16,
                                alignment: WrapAlignment.center,
                                children:
                                    bs.scheduleListModel.schedules.map((i) {
                                  if ((i.value ?? "").isEmpty) {
                                    return const SizedBox();
                                  }
                                  return ValueListenableBuilder(
                                    valueListenable: selectedSchedule,
                                    builder: (context, value, child) => _button(
                                      title: i.value ?? "",
                                      onPressed: () {
                                        selectedSchedule.value = i;
                                      },
                                      isSelected: i.id.toString() ==
                                          (value?.id).toString(),
                                    ),
                                  );
                                }).toList(),
                              ),
                            ),
                          );
                        }),
                        16.toHeight,
                        SizedBox(
                            width: double.infinity,
                            child: ElevatedButton(
                              onPressed: () {
                                if (selectedSchedule.value == null) {
                                  LocalKeys.selectASchedule.showToast();
                                  return;
                                }
                                svm.selectedSchedule.value =
                                    selectedSchedule.value;
                                svm.selectedDate.value = selectedDate.value;
                                context.pop;
                              },
                              child: Text(LocalKeys.complete),
                            )),
                        20.toHeight,
                      ],
                    );
                  }
                  return Column(
                    children: [
                      ValueListenableBuilder(
                          valueListenable: selectedDate,
                          builder: (context, value, child) {
                            return Theme(
                              data: context.cTheme,
                              child: CalendarDatePicker(
                                  initialDate: value,
                                  firstDate: now,
                                  lastDate: DateTime(now.year, now.month + 3),
                                  onDateChanged: (date) {
                                    selectedDate.value = date;
                                  }),
                            );
                          }),
                      SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: () {
                              if (selectedDate.value == null) {
                                LocalKeys.selectDate.showToast();
                                return;
                              }
                              if (Provider.of<BookingScheduleService>(context,
                                      listen: false)
                                  .ignoreScheduleFetch(
                                      selectedDate.value,
                                      svm.selectedService.value?.provider?.id,
                                      svm.selectedService.value?.admin?.id)) {
                                debugPrint(
                                    "Skipping schedule fetch".toString());
                                svm.dateScheduleType.value =
                                    SelectingScheduleType.schedule;
                                return;
                              }
                              svm.dateScheduleType.value =
                                  SelectingScheduleType.schedule;
                              final pId = Provider.of<ServiceDetailsService>(
                                      context,
                                      listen: false)
                                  .serviceDetailsModel(
                                      svm.selectedService.value?.id)
                                  .allServices
                                  ?.provider
                                  ?.id;
                              selectedSchedule.value = null;
                              Provider.of<BookingScheduleService>(context,
                                      listen: false)
                                  .fetchScheduleList(
                                      selectedDate.value!,
                                      svm.selectedService.value?.provider?.id ??
                                          pId,
                                      admin:
                                          svm.selectedService.value?.admin?.id);
                            },
                            child: Text(LocalKeys.continueO),
                          )),
                      20.toHeight,
                    ],
                  );
                },
              ),
            ],
          ),
        );
      },
    );
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
