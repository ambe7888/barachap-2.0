import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/marquee.dart';
import 'package:prohand/utils/components/text_skeleton.dart';

class MyServiceTileSkeleton extends StatelessWidget {
  const MyServiceTileSkeleton({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SquircleContainer(
                height: 48,
                width: 48,
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
                    TextSkeleton(
                      height: 16,
                      width: context.width * .7,
                    ),
                    4.toHeight,
                    TextSkeleton(
                      height: 16,
                      width: context.width * .4,
                    ),
                    8.toHeight,
                    const Wrap(
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
                    )
                  ],
                ),
              )
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
