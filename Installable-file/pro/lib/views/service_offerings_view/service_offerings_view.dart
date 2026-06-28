import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/views/service_offerings_view/components/offerings_tile.dart';
import 'package:provider/provider.dart';

import '../../services/service_services/add_edit_service_service.dart';
import '../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'components/add_offer_button.dart';

class ServiceOfferingsView extends StatelessWidget {
  const ServiceOfferingsView({super.key});

  @override
  Widget build(BuildContext context) {
    final aes = AddEditServiceViewModel.instance;
    return Consumer<AddEditServiceService>(builder: (context, ae, child) {
      return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              LocalKeys.serviceOfferings,
              style: context.titleLarge?.bold,
            ),
            Text(
              LocalKeys.serviceOfferingsDesc,
              style: context.bodySmall
                  ?.copyWith(color: context.color.primaryContrastColor),
            ),
            24.toHeight,
            ValueListenableBuilder(
              valueListenable: aes.includes,
              builder: (context, value, child) => Expanded(
                  child: ListView.separated(
                      itemBuilder: (context, index) {
                        debugPrint("index is $index".toString());
                        return OfferingsTile(
                          id: index,
                          title: value[index]["include_service_title"] ?? "",
                          description:
                              value[index]["include_service_description"] ?? "",
                        );
                      },
                      separatorBuilder: (context, index) => 12.toHeight,
                      itemCount: value.length)),
            ),
            24.toHeight,
            const AddOfferButton(),
          ],
        ),
      );
    });
  }
}
