import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';

import '../../../helper/constant_helper.dart';
import 'service_tile_skeleton.dart';

class ServiceResultSkeleton extends StatelessWidget {
  const ServiceResultSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 1500,
      child: ListView.separated(
              physics: const NeverScrollableScrollPhysics(),
              itemBuilder: (context, index) => const ServiceTileSkeleton(),
              separatorBuilder: (context, index) => Divider(
                    color: color.primaryBorderColor,
                    height: 2,
                  ).hp20,
              itemCount: 15)
          .shim,
    );
  }
}
