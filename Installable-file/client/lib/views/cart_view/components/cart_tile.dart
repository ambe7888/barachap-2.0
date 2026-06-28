import 'dart:math';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/schedule_list_model.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';
import 'package:prohandy_client/views/booking_summery_view/booking_summery_view.dart';

import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/custom_squircle_widget.dart';

class CartTile extends StatelessWidget {
  final String? serviceTitle;
  final String? serviceImage;
  final dynamic serviceId;
  final num totalAmount;
  final dynamic cartItem;
  const CartTile(
      {super.key,
      this.serviceTitle,
      this.serviceImage,
      this.serviceId,
      required this.totalAmount,
      this.cartItem});

  @override
  Widget build(BuildContext context) {
    final date = DateTime.tryParse(cartItem["date"].toString());
    final schedule = cartItem["schedule"] == null
        ? null
        : Schedule.fromJson(cartItem["schedule"]);
    return GestureDetector(
      onTap: () {
        ServiceBookingViewModel.dispose;
        ServiceBookingViewModel.instance.initCartItem(cartItem);
        context.toPage(const BookingSummeryView());
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CustomNetworkImage(
              height: 40,
              width: 40,
              radius: 10,
              fit: BoxFit.cover,
              imageUrl: serviceImage,
            ),
            8.toWidth,
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    serviceTitle ?? "---",
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: context.titleSmall?.bold,
                  ),
                  4.toHeight,
                  Wrap(
                    spacing: 8,
                    runSpacing: 4,
                    crossAxisAlignment: WrapCrossAlignment.center,
                    children: [
                      if (date != null)
                        Text(
                          date == null
                              ? LocalKeys.na
                              : DateFormat("EEE, dd MMMM",
                                      context.dProvider.languageSlug)
                                  .format(date),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: context.titleSmall?.bold.copyWith(
                              color: context.color.tertiaryContrastColo),
                        ),
                      if (schedule?.value != null)
                        SquircleContainer(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 8, vertical: 4),
                            borderColor: context.color.primaryBorderColor,
                            radius: 4,
                            child: Text(
                              schedule!.value!,
                              style: context.bodySmall?.bold.copyWith(
                                  color: context.color.secondaryContrastColor),
                            ))
                    ],
                  ),
                ],
              ),
            ),
            8.toWidth,
            TextButton.icon(
              onPressed: () {
                ServiceBookingViewModel.dispose;
                ServiceBookingViewModel.instance.initCartItem(cartItem);
                context.toPage(const BookingSummeryView());
              },
              label: Text(totalAmount.cur),
              icon: Transform.rotate(
                angle: context.dProvider.textDirectionRight ? pi : 0,
                child: SvgAssets.chevron.toSVGSized(
                  20,
                  color: primaryColor,
                ),
              ),
              iconAlignment: IconAlignment.end,
            )
          ],
        ),
      ),
    );
  }
}
