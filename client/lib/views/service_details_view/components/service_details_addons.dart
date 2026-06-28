import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/service/service_details_model.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsAddons extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceDetailsAddons({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    return (serviceDetails.allServices?.addons ?? []).isEmpty
        ? EmptyElement(text: LocalKeys.noAddonsFound)
        : Wrap(
            runSpacing: 12,
            children: (serviceDetails.allServices?.addons ?? []).map((addon) {
              return SquircleContainer(
                  radius: 8,
                  padding: 8.paddingAll,
                  width: double.infinity,
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
                        style: context.titleSmall?.bold,
                      ),
                      4.toHeight,
                      Text(
                        addon.description ?? "---",
                        style: context.bodySmall,
                      ),
                      12.toHeight,
                      Text(
                        addon.price.cur,
                        style: context.titleSmall?.bold
                            .copyWith(color: primaryColor),
                      ),
                    ],
                  ));
            }).toList(),
          );
  }
}
