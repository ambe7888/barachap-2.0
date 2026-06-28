import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/empty_element.dart';
import 'package:prohandy_client/view_models/service_details_view_model/service_details_view_model.dart';

import '../../../models/service/service_details_model.dart';

class ServiceDetailsFaqTab extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceDetailsFaqTab({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    final sdm = ServiceDetailsViewModel.instance;
    return (serviceDetails.allServices?.faqs ?? []).isEmpty
        ? EmptyElement(text: LocalKeys.noFaqAdded)
        : Wrap(
            spacing: 12,
            runSpacing: 12,
            children: (serviceDetails.allServices?.faqs ?? []).map((faq) {
              return ValueListenableBuilder(
                valueListenable: sdm.selectedFAQ,
                builder: (context, value, child) {
                  final isSelected = value == faq;
                  return GestureDetector(
                    onTap: () {
                      if (isSelected) {
                        sdm.selectedFAQ.value = null;
                        return;
                      }
                      sdm.selectedFAQ.value = faq;
                    },
                    child: SquircleContainer(
                        radius: 10,
                        borderColor: context.color.primaryBorderColor,
                        padding: const EdgeInsets.symmetric(
                            horizontal: 20, vertical: 16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Expanded(
                                    flex: 1,
                                    child: Text(
                                      faq.title ?? "---",
                                      style: context.titleSmall?.bold,
                                    )),
                                SvgAssets.arrowDown.toSVGSized(
                                  20,
                                  color: context.color.tertiaryContrastColo,
                                ),
                              ],
                            ),
                            if (isSelected) ...[
                              12.toHeight,
                              const SizedBox().divider,
                              8.toHeight,
                              Text(
                                faq.description ?? "---",
                                style: context.bodySmall,
                              )
                            ]
                          ],
                        )),
                  );
                },
              );
            }).toList(),
          );
  }
}
