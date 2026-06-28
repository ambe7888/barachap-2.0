import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';

import '../../helper/local_keys.g.dart';

class ServiceSchedules extends StatelessWidget {
  const ServiceSchedules({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Divider(
            height: 8,
            thickness: 8,
            color: context.color.backgroundColor,
          ),
          Padding(
            padding: const EdgeInsets.symmetric(vertical: 12),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.scheduleAvailability,
                  style: context.titleLarge?.bold,
                ).hp20,
                Text(
                  LocalKeys.scheduleAvailabilityDesc,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.primaryContrastColor),
                ).hp20,
                24.toHeight,
              ],
            ),
          )
        ],
      ),
    );
  }
}
