import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../models/service_models/service_details_model.dart';

class ServiceDetailsOffers extends StatelessWidget {
  final List<AdditionalInfo> offers;
  const ServiceDetailsOffers({super.key, required this.offers});

  @override
  Widget build(BuildContext context) {
    return offers.isEmpty
        ? const SizedBox()
        : Container(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            margin: const EdgeInsets.only(top: 8),
            color: context.color.accentContrastColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.ourOffers,
                  style: context.titleMedium?.bold,
                ),
                12.toHeight,
                Wrap(
                  runSpacing: 8,
                  children: offers.map((e) {
                    final showDesc = ValueNotifier(false);
                    return GestureDetector(
                      onTap: () {
                        if ((e.description ?? "").isNotEmpty) {
                          showDesc.value = !showDesc.value;
                        }
                      },
                      child: SquircleContainer(
                        radius: 8,
                        padding: 4.paddingAll,
                        color: context.color.mutedSuccessColor,
                        child: ValueListenableBuilder(
                            valueListenable: showDesc,
                            builder: (context, show, child) {
                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(children: [
                                    Icon(
                                      Icons.done_rounded,
                                      color: context.color.primarySuccessColor,
                                    ),
                                    8.toWidth,
                                    Expanded(
                                      flex: 1,
                                      child: Text(
                                        e.title ?? "",
                                        style: context.bodySmall?.copyWith(
                                            color: context
                                                .color.secondaryContrastColor),
                                      ),
                                    ),
                                    if ((e.description ?? "").isNotEmpty)
                                      Icon(
                                        show
                                            ? Icons.keyboard_arrow_up
                                            : Icons.keyboard_arrow_down,
                                        color: context
                                            .color.secondaryContrastColor,
                                      )
                                  ]),
                                  if (show)
                                    Padding(
                                      padding: 10.paddingH,
                                      child: Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            6.toHeight,
                                            Divider(
                                              thickness: 2,
                                              height: 2,
                                              color: context
                                                  .color.mutedContrastColor,
                                            ),
                                            8.toHeight,
                                            Text(
                                              e.description!,
                                              style: context.bodySmall,
                                            ),
                                            8.toHeight,
                                          ]),
                                    )
                                ],
                              );
                            }),
                      ),
                    );
                  }).toList(),
                ),
              ],
            ),
          );
  }
}
