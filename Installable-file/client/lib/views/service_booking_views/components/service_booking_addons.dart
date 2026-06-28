import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/service/service_details_model.dart';
import '../../../services/booking_services/booking_addons_service.dart';
import '../../../services/home_services/service_details_service.dart';
import '../../../utils/components/field_label.dart';

class ServiceBookingAddons extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceBookingAddons({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final addons = serviceDetails.allServices?.addons ?? [];
      return addons.isEmpty
          ? const SizedBox()
          : Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                FieldLabel(
                  label: LocalKeys.addons,
                ),
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Wrap(
                    spacing: 12,
                    children: addons.map((addon) {
                      return SquircleContainer(
                          radius: 8,
                          padding: 8.paddingAll,
                          borderColor: context.color.primaryBorderColor,
                          constraints: BoxConstraints(
                              minWidth: context.width / 2.5 > 250
                                  ? 250
                                  : context.width / 2.5),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                addon.title ?? "---",
                                style: context.titleSmall,
                              ),
                              4.toHeight,
                              Text(
                                addon.price.cur,
                                style: context.titleSmall?.bold
                                    .copyWith(color: primaryColor),
                              ),
                              24.toHeight,
                              Consumer<BookingAddonsService>(
                                  builder: (context, ba, child) {
                                return Row(
                                  children: [
                                    GestureDetector(
                                      onTap: () {
                                        ba.decreaseAddonQ(addon.id, addon.title,
                                            addon.price, addon.serviceId);
                                      },
                                      child: SquircleContainer(
                                          radius: 6,
                                          padding: 4.paddingAll,
                                          borderColor:
                                              context.color.primaryBorderColor,
                                          child: Icon(
                                            Icons.remove_rounded,
                                            color: context
                                                .color.tertiaryContrastColo,
                                          )),
                                    ),
                                    Padding(
                                      padding: 12.paddingH,
                                      child: Text(
                                          ba.quantity(addon.id).toString(),
                                          style: context.titleMedium?.bold),
                                    ),
                                    GestureDetector(
                                      onTap: () {
                                        ba.increaseAddonQ(addon.id, addon.title,
                                            addon.price, addon.serviceId);
                                      },
                                      child: SquircleContainer(
                                          radius: 6,
                                          padding: 4.paddingAll,
                                          borderColor:
                                              context.color.primarySuccessColor,
                                          child: Icon(
                                            Icons.add_rounded,
                                            color: context
                                                .color.primarySuccessColor,
                                          )),
                                    ),
                                  ],
                                );
                              })
                            ],
                          ));
                    }).toList(),
                  ),
                )
              ],
            );
    });
  }
}
