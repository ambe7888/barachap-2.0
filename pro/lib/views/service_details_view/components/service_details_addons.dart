import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/service_services/service_details_service.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsAddons extends StatelessWidget {
  const ServiceDetailsAddons({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      return (sd.serviceDetailsModel.allServices?.addons ?? []).isEmpty
          ? SizedBox(
              width: double.infinity,
              height: 250,
              child: EmptyElement(text: LocalKeys.noAddonsFound))
          : Wrap(
              runSpacing: 12,
              children: (sd.serviceDetailsModel.allServices?.addons ?? [])
                  .map((addon) {
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
    });
  }
}
