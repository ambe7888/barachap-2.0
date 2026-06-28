import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/date_schedule_view_model/date_schedule_view_model.dart';
import 'package:provider/provider.dart';

import '../../customizations/colors.dart';
import '../../helper/local_keys.g.dart';
import '../../helper/svg_assets.dart';
import '../../services/date_schedule_service.dart';
import '../../services/schedule_services/schedule_list_service.dart';
import '../../utils/components/custom_squircle_widget.dart';
import '../../utils/components/empty_widget.dart';
import 'components/schedule_grid_skeleton.dart';
import 'components/select_schedule_button.dart';
import 'components/selected_schedule.dart';

class DateScheduleView extends StatelessWidget {
  const DateScheduleView({super.key});

  @override
  Widget build(BuildContext context) {
    final now = DateTime.now();
    final dsm = DateScheduleViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => DateScheduleService(),
      child: Scaffold(
          backgroundColor: context.color.accentContrastColor,
          appBar: AppBar(
            leading: const NavigationPopIcon(),
          ),
          body: CustomRefreshIndicator(
            onRefresh: () async {
              await Provider.of<ScheduleListService>(context, listen: false)
                  .fetchScheduleList();
            },
            child: ValueListenableBuilder(
              valueListenable: dsm.selectedDay,
              builder: (context, day, child) => SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Divider(
                      height: 8,
                      thickness: 8,
                      color: context.color.backgroundColor,
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            LocalKeys.scheduleAvailability,
                            style: context.titleLarge?.bold,
                          ).hp20,
                          Text(
                            LocalKeys.scheduleAvailabilityDesc,
                            style: context.bodySmall?.copyWith(
                                color: context.color.primaryContrastColor),
                          ).hp20,
                          24.toHeight,
                          SingleChildScrollView(
                            padding: 24.paddingH,
                            scrollDirection: Axis.horizontal,
                            child: Wrap(
                              spacing: 8,
                              children: List.generate(7, (index) {
                                final weekday = now.add(Duration(days: index));
                                final isSelected =
                                    day.weekday == weekday.weekday;
                                final weekdayName = DateFormat(
                                        'EEEE', context.dProvider.languageSlug)
                                    .format(weekday);

                                return GestureDetector(
                                  onTap: () {
                                    dsm.selectedDay.value = weekday;
                                  },
                                  child: SquircleContainer(
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 12, vertical: 6),
                                      borderColor: isSelected
                                          ? primaryColor
                                          : context.color.primaryBorderColor,
                                      color: isSelected ? primaryColor : null,
                                      radius: 12,
                                      child: FittedBox(
                                        child: Row(
                                          children: [
                                            SvgAssets.calendar.toSVGSized(20,
                                                color: isSelected
                                                    ? context.color
                                                        .accentContrastColor
                                                    : context.color
                                                        .tertiaryContrastColo),
                                            6.toWidth,
                                            Text(
                                              weekdayName,
                                              style: context.titleSmall?.bold
                                                  .copyWith(
                                                      color: isSelected
                                                          ? context.color
                                                              .accentContrastColor
                                                          : null),
                                            ),
                                          ],
                                        ),
                                      )),
                                );
                              }),
                            ),
                          ),
                          16.toHeight,
                          Center(
                            child: Consumer<ScheduleListService>(
                                builder: (context, ds, child) {
                              return CustomFutureWidget(
                                  function: ds.shouldAutoFetch
                                      ? ds.fetchScheduleList()
                                      : null,
                                  shimmer: const ScheduleGridSkeleton(),
                                  child: Wrap(
                                    spacing: 8,
                                    runSpacing: 16,
                                    children: [
                                      if (ds.scheduleListModel.schedules
                                              ?.isEmpty ??
                                          true)
                                        SizedBox(
                                          height: 300,
                                          child: EmptyWidget(
                                            title: LocalKeys.noScheduleFound,
                                            physics:
                                                const NeverScrollableScrollPhysics(),
                                          ),
                                        ),
                                      ...(ds.scheduleListModel.schedules ?? [])
                                          .map((e) {
                                        return SelectedSchedule(
                                          slot: e.schedule ?? "---",
                                          schedule: e,
                                        );
                                      }),
                                      const SelectScheduleButton(),
                                    ],
                                  ));
                            }),
                          ).hp20,
                        ],
                      ),
                    )
                  ],
                ),
              ),
            ),
          )),
    );
  }
}
