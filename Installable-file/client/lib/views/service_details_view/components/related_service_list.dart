import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../models/home_models/services_list_model.dart';
import '../../../utils/service_card/service_card.dart';

class RelatedServiceList extends StatelessWidget {
  final List<ServiceModel> relatedServices;
  const RelatedServiceList({super.key, required this.relatedServices});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Text(
              LocalKeys.relatedServices,
              style: context.titleMedium?.bold,
            ),
          ),
          8.toHeight,
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Wrap(
              spacing: 16,
              children: relatedServices
                  .map((e) => ServiceCard(
                        service: e,
                      ))
                  .toList(),
            ),
          ),
        ],
      ),
    );
  }
}
