import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/view_models/service_details_view_model/service_details_view_model.dart';

class ServiceDetailsTabsTitles extends StatelessWidget {
  const ServiceDetailsTabsTitles({super.key});

  @override
  Widget build(BuildContext context) {
    final sdm = ServiceDetailsViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: sdm.selectedTab,
      builder: (context, tab, child) {
        return Container(
          margin: 24.paddingH,
          decoration: BoxDecoration(
              border: Border(
                  bottom: BorderSide(
                      color: context.color.primaryBorderColor, width: 2))),
          child: Row(
            children: ServiceDetailsTabsTypes.values.map((e) {
              final isSelected = e == tab;
              return GestureDetector(
                onTap: () {
                  if (isSelected) {
                    return;
                  }
                  sdm.selectedTab.value = e;
                },
                child: Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 8, vertical: 14),
                  decoration: BoxDecoration(
                      border: Border(
                          bottom: BorderSide(
                    color: isSelected ? primaryColor : Colors.transparent,
                    width: 4,
                  ))),
                  child: Text(
                    serviceDetailsTabs.reverse[e] ?? "---",
                    style: context.titleSmall
                        ?.copyWith(color: isSelected ? primaryColor : null),
                  ),
                ),
              );
            }).toList(),
          ),
        );
      },
    );
  }
}
