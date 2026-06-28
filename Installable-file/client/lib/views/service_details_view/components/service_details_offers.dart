import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../../helper/svg_assets.dart';
import '../../../models/service/service_details_model.dart';
import '../../../utils/components/custom_squircle_widget.dart';

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
                        color: mutedPrimaryColor,
                        child: ValueListenableBuilder(
                            valueListenable: showDesc,
                            builder: (context, show, child) {
                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(children: [
                                    const Icon(
                                      Icons.done_rounded,
                                      color: primaryColor,
                                    ),
                                    8.toWidth,
                                    Expanded(
                                      flex: 1,
                                      child: Text(
                                        e.title ?? "",
                                        style: context.titleSmall
                                            ?.copyWith(fontSize: 12)
                                            .bold6,
                                      ),
                                    ),
                                    if ((e.description ?? "").isNotEmpty)
                                      SvgAssets.arrowDown.toSVGSized(
                                        20,
                                        color:
                                            context.color.tertiaryContrastColo,
                                      ),
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
                                              thickness: 1,
                                              height: 1,
                                              color: color.tertiaryContrastColo
                                                  .withOpacity(.3),
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
