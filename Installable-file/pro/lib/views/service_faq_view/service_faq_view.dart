import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/service_services/add_edit_service_service.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:prohand/views/service_faq_view/components/faq_tile.dart';
import 'package:provider/provider.dart';

import 'components/add_faq_button.dart';

class ServiceFaqView extends StatelessWidget {
  const ServiceFaqView({super.key});

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
              LocalKeys.serviceFAQ,
              style: context.titleLarge?.bold,
            ),
            Text(
              LocalKeys.serviceFAQDsc,
              style: context.bodySmall
                  ?.copyWith(color: context.color.primaryContrastColor),
            ),
            24.toHeight,
            ValueListenableBuilder(
              valueListenable: aes.faq,
              builder: (context, value, child) => Expanded(
                  child: ListView.separated(
                      padding: const EdgeInsets.symmetric(vertical: 2),
                      itemBuilder: (context, index) {
                        debugPrint("index is $index".toString());
                        return FaqTile(
                          id: index,
                          title: value[index]["faq_service_title"] ?? "",
                          answer: value[index]["faq_service_description"] ?? "",
                        );
                      },
                      separatorBuilder: (context, index) => 12.toHeight,
                      itemCount: value.length)),
            ),
            24.toHeight,
            const AddFAQButton(),
          ],
        ),
      );
    });
  }
}
