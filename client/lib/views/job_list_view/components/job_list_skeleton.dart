import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/utils/components/text_skeleton.dart';

class JobListSkeleton extends StatelessWidget {
  const JobListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
            physics: const NeverScrollableScrollPhysics(),
            itemBuilder: (context, index) {
              return Container(
                  margin: 25.paddingH,
                  padding: 16.paddingAll,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Wrap(
                        spacing: 6,
                        crossAxisAlignment: WrapCrossAlignment.center,
                        children: [
                          TextSkeleton(
                            height: 12,
                            width: 30,
                          ),
                          TextSkeleton(
                            height: 12,
                            width: 44,
                          )
                        ],
                      ),
                      8.toHeight,
                      TextSkeleton(
                        height: 16,
                        width: context.width * .5,
                      ),
                      10.toHeight,
                      const Wrap(
                        spacing: 4,
                        crossAxisAlignment: WrapCrossAlignment.center,
                        children: [
                          TextSkeleton(
                            height: 14,
                            width: 64,
                          ),
                        ],
                      )
                    ],
                  ));
            },
            separatorBuilder: (context, index) => Padding(
                  padding: 24.paddingH,
                  child: Divider(
                    height: 2,
                    thickness: 2,
                    color: context.color.primaryBorderColor,
                  ),
                ),
            itemCount: 10)
        .shim;
  }
}
