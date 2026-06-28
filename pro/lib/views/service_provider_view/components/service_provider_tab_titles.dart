import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../view_models/service_provider_view_model/service_provider_view_model.dart';

class ServiceProviderTabTitles extends StatelessWidget {
  const ServiceProviderTabTitles({super.key});

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: spm.selectedTab,
      builder: (context, tab, child) {
        return Container(
          decoration: BoxDecoration(
              color: context.color.accentContrastColor,
              border: Border(
                  bottom: BorderSide(
                      color: context.color.primaryBorderColor, width: 2))),
          child: Row(
            children: ServiceProviderTabsTypes.values.map((e) {
              final isSelected = e == tab;
              return Expanded(
                flex: 1,
                child: GestureDetector(
                  onTap: () {
                    if (isSelected) {
                      return;
                    }
                    spm.selectedTab.value = e;
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 14),
                    alignment: Alignment.center,
                    decoration: BoxDecoration(
                        border: Border(
                            bottom: BorderSide(
                      color: isSelected ? primaryColor : Colors.transparent,
                      width: 4,
                    ))),
                    child: Text(
                      serviceProviderTabs.reverse[e] ?? "---",
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: context.titleSmall
                          ?.copyWith(color: isSelected ? primaryColor : null),
                    ),
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
