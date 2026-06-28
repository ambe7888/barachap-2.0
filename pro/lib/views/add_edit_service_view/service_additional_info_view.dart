import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:prohand/views/service_excludes_view/service_excludes_view.dart';
import 'package:prohand/views/service_faq_view/service_faq_view.dart';
import 'package:prohand/views/service_offerings_view/service_offerings_view.dart';
import 'package:provider/provider.dart';

import '../../services/service_services/add_edit_service_service.dart';

class ServiceAdditionalInfoView extends StatelessWidget {
  const ServiceAdditionalInfoView({super.key});

  @override
  Widget build(BuildContext context) {
    final aes = AddEditServiceViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: aes.selectedAdInfoType,
      builder: (context, value, child) {
        return Column(
          children: [
            Divider(
              height: 8,
              thickness: 8,
              color: context.color.backgroundColor,
            ),
            12.toHeight,
            Row(
              children: [
                Expanded(
                    flex: 1,
                    child: _button(
                        title: LocalKeys.offerings,
                        adInfoType: AdInfoType.includes,
                        isSelected: value == AdInfoType.includes)),
                12.toWidth,
                Expanded(
                    flex: 1,
                    child: _button(
                        title: LocalKeys.excludes,
                        adInfoType: AdInfoType.excludes,
                        isSelected: value == AdInfoType.excludes)),
                12.toWidth,
                Expanded(
                    flex: 1,
                    child: _button(
                        title: LocalKeys.faq,
                        adInfoType: AdInfoType.faq,
                        isSelected: value == AdInfoType.faq)),
                12.toWidth,
              ],
            ).hp20,
            Consumer<AddEditServiceService>(
              builder: (context, ae, child) {
                if (value == AdInfoType.includes) {
                  return const Expanded(child: ServiceOfferingsView());
                }
                if (value == AdInfoType.excludes) {
                  return const Expanded(child: ServiceExcludesView());
                }
                return const Expanded(child: ServiceFaqView());
              },
            )
          ],
        );
      },
    );
  }

  _button({
    required String title,
    required AdInfoType adInfoType,
    bool isSelected = false,
  }) {
    return isSelected
        ? ElevatedButton.icon(
            onPressed: () {},
            label: Text(title),
          )
        : OutlinedButton.icon(
            onPressed: () {
              AddEditServiceViewModel.instance.selectedAdInfoType.value =
                  adInfoType;
            },
            label: Text(title),
          );
  }
}
