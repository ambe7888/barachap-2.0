import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

import '../../../utils/components/custom_squircle_widget.dart';

class HomeOrderInProgressSkeleton extends StatelessWidget {
  const HomeOrderInProgressSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: 16.paddingV,
      width: double.infinity,
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          TextSkeleton(
            height: 20,
            width: context.width * .4,
          ).hp20,
          12.toHeight,
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            physics: const NeverScrollableScrollPhysics(),
            padding: 24.paddingH,
            child: Wrap(
              spacing: 12,
              children: List.generate(2, (i) {
                return SquircleContainer(
                    width: context.width - 72,
                    padding: 12.paddingAll,
                    radius: 10,
                    borderColor: context.color.primaryBorderColor,
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SquircleContainer(
                          height: 72,
                          width: 72,
                          radius: 10,
                          color: context.color.mutedContrastColor,
                          child: const SizedBox(),
                        ),
                        12.toWidth,
                        Expanded(
                          flex: 1,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Wrap(
                                spacing: 6,
                                runSpacing: 6,
                                children: [
                                  TextSkeleton(
                                    height: 20,
                                    width: 32,
                                  ),
                                  TextSkeleton(
                                    height: 20,
                                    width: 44,
                                  ),
                                ],
                              ),
                              6.toHeight,
                              TextSkeleton(
                                height: 16,
                                width: context.width - 200,
                              ),
                              6.toHeight,
                              const TextSkeleton(
                                height: 20,
                                width: 150,
                              ),
                            ],
                          ),
                        )
                      ],
                    ));
              }),
            ),
          )
        ],
      ),
    );
  }
}
