import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

import '../../../utils/components/custom_squircle_widget.dart';
import '../../../utils/components/marquee.dart';

class ServiceDetailsSkeleton extends StatelessWidget {
  const ServiceDetailsSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              height: 250,
              width: double.infinity,
              color: context.color.mutedContrastColor,
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      TextSkeleton(
                        height: 16,
                        width: 120,
                      ),
                      TextSkeleton(
                        height: 20,
                        width: 40,
                      ),
                    ],
                  ),
                  16.toHeight,
                  const SizedBox().divider,
                  12.toHeight,
                  const TextSkeleton(
                    height: 20,
                    width: 120,
                  ),
                  6.toHeight,
                  const Wrap(
                    spacing: 6,
                    children: [
                      TextSkeleton(
                        height: 16,
                        width: 26,
                      ),
                      TextSkeleton(
                        height: 16,
                        width: 26,
                      ),
                    ],
                  ),
                  12.toHeight,
                  Marquee(
                      child: Wrap(
                    children: [
                      subInfo(
                        context,
                      ),
                      subInfo(
                        context,
                        isMiddle: true,
                      ),
                      subInfo(
                        context,
                      ),
                    ],
                  ))
                ],
              ),
            ),
            8.toHeight,
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Wrap(
                    spacing: 12,
                    children: [
                      TextSkeleton(
                        height: 16,
                        width: 60,
                      ),
                      TextSkeleton(
                        height: 16,
                        width: 40,
                      ),
                      TextSkeleton(
                        height: 16,
                        width: 48,
                      ),
                    ],
                  ),
                  16.toHeight,
                  const SizedBox().divider,
                  16.toHeight,
                  TextSkeleton(
                    height: 14,
                    width: context.width * .8,
                  ),
                  4.toHeight,
                  TextSkeleton(
                    height: 14,
                    width: context.width * .7,
                  ),
                  4.toHeight,
                  TextSkeleton(
                    height: 14,
                    width: context.width * .4,
                  ),
                  4.toHeight,
                ],
              ),
            ),
            8.toHeight,
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const TextSkeleton(
                    height: 16,
                    width: 80,
                  ),
                  16.toHeight,
                  Wrap(
                    runSpacing: 8,
                    children: List.generate(
                        3,
                        (i) => SquircleContainer(
                              radius: 8,
                              padding: 4.paddingAll,
                              width: double.infinity,
                              color: context.color.mutedSuccessColor,
                              child: const SizedBox(
                                height: 12,
                              ),
                            )),
                  ),
                ],
              ),
            ),
            8.toHeight,
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const TextSkeleton(
                    height: 16,
                    width: 80,
                  ),
                  16.toHeight,
                  Wrap(
                    runSpacing: 8,
                    children: List.generate(
                        2,
                        (i) => SquircleContainer(
                              radius: 8,
                              padding: 4.paddingAll,
                              width: double.infinity,
                              color: context.color.backgroundColor,
                              child: const SizedBox(
                                height: 12,
                              ),
                            )),
                  ),
                ],
              ),
            ),
          ],
        ).shim,
      ),
    );
  }

  Widget subInfo(BuildContext context, {bool isMiddle = false}) {
    return Container(
      alignment: Alignment.center,
      padding: 6.paddingH,
      decoration: BoxDecoration(
          border: !isMiddle
              ? null
              : Border(
                  left: BorderSide(
                      color: context.color.primaryBorderColor, width: 2),
                  right: BorderSide(
                      color: context.color.primaryBorderColor, width: 2),
                )),
      constraints: BoxConstraints(
        minWidth: (context.width - 52) / 3,
      ),
      child: const Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          TextSkeleton(
            height: 16,
            width: 88,
          ),
        ],
      ),
    );
  }
}
