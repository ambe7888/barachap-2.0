import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/service_services/service_details_service.dart';
import '../../../utils/components/empty_element.dart';

class ServiceDetailsFaqTab extends StatelessWidget {
  const ServiceDetailsFaqTab({super.key});

  @override
  Widget build(BuildContext context) {
    final sdm = ServiceDetailsViewModel.instance;

    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final faqs = sd.serviceDetailsModel.allServices?.faqs ?? [];
      return (faqs ?? []).isEmpty
          ? SizedBox(
              width: double.infinity,
              height: 250,
              child: EmptyElement(text: LocalKeys.noFaqAdded))
          : Wrap(
              spacing: 12,
              runSpacing: 12,
              children: faqs.map((faq) {
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
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
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
                                Divider(
                                  color: context.color.mutedContrastColor,
                                  thickness: 1,
                                  height: 1,
                                ),
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
    });
  }
}
