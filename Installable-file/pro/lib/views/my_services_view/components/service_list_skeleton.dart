import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/views/my_services_view/components/my_service_tile_skeleton.dart';

class ServiceListSkeleton extends StatelessWidget {
  const ServiceListSkeleton({super.key});

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: SingleChildScrollView(
        physics: const NeverScrollableScrollPhysics(),
        child: Wrap(
          children: [
            const MyServiceTileSkeleton(),
            0.toWidth.divider.hp20,
            const MyServiceTileSkeleton(),
            0.toWidth.divider.hp20,
            const MyServiceTileSkeleton(),
            0.toWidth.divider.hp20,
            const MyServiceTileSkeleton(),
            0.toWidth.divider.hp20,
            const MyServiceTileSkeleton(),
            0.toWidth.divider.hp20,
          ],
        ).shim,
      ),
    );
  }
}
