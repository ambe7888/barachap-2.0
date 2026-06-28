import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/schedule_list_model.dart';
import 'package:prohandy_client/views/service_booking_views/components/service_booking_date.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class BookingSummeryDateSchedule extends StatelessWidget {
  final ValueNotifier<DateTime?> date;
  final ValueNotifier<Schedule?> schedule;
  const BookingSummeryDateSchedule(
      {super.key, required this.date, required this.schedule});

  @override
  Widget build(BuildContext context) {
    return Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Row(
          children: [
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    LocalKeys.serviceDate,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: context.bodySmall
                        ?.copyWith(color: context.color.tertiaryContrastColo),
                  ),
                  6.toHeight,
                  ValueListenableBuilder(
                      valueListenable: date,
                      builder: (context, value, child) {
                        return Wrap(
                            spacing: 8,
                            crossAxisAlignment: WrapCrossAlignment.center,
                            children: [
                              Text(
                                value == null
                                    ? "---"
                                    : DateFormat("EEE, dd MMMM",
                                            context.dProvider.languageSlug)
                                        .format(value),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                                style: context.titleSmall?.bold,
                              ),
                              SquircleContainer(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 8, vertical: 4),
                                  borderColor: context.color.primaryBorderColor,
                                  radius: 4,
                                  child: Text(
                                    schedule.value?.value ?? "---",
                                    style: context.bodySmall?.bold.copyWith(
                                        color: context
                                            .color.secondaryContrastColor),
                                  ))
                            ]);
                      }),
                ],
              ),
            ),
            12.toWidth,
            GestureDetector(
              onTap: () {
                const ServiceBookingDate().onTap(context);
              },
              child: Container(
                padding: 10.paddingAll,
                decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: primaryColor)),
                child: SvgAssets.pencil.toSVGSized(24, color: primaryColor),
              ),
            ),
          ],
        ));
  }
}
