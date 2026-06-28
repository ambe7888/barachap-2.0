import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/view_models/service_details_view_model/service_details_view_model.dart';

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

class SliverHeaderDelegateComponent extends SliverPersistentHeaderDelegate {
  final double expandedHeight;

  const SliverHeaderDelegateComponent({required this.expandedHeight});

  @override
  Widget build(
      BuildContext context, double shrinkOffset, bool overlapsContent) {
    final sdm = ServiceDetailsViewModel.instance;
    final appBarSize = expandedHeight - shrinkOffset;
    final proportion = 2 - (expandedHeight / appBarSize);
    final percent = proportion < 0 || proportion > 1 ? 0.0 : proportion;
    return StatefulBuilder(
      builder: (BuildContext context, StateSetter setState) => SizedBox(
        height: expandedHeight,
        child: Stack(
          clipBehavior: Clip.none,
          children: [
            ValueListenableBuilder(
              valueListenable: sdm.selectedTab,
              builder: (context, tab, child) {
                return Container(
                  margin: 24.paddingH,
                  color: context.color.accentContrastColor,
                  decoration: BoxDecoration(
                      border: Border(
                          bottom: BorderSide(
                              color: context.color.primaryBorderColor))),
                  child: Row(
                    children: ServiceDetailsTabsTypes.values.map((e) {
                      final isSelected = e == tab;
                      return Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 16, vertical: 6),
                        decoration: BoxDecoration(
                            border: Border(
                                bottom: BorderSide(
                          color: isSelected ? primaryColor : Colors.transparent,
                          width: 4,
                        ))),
                        child: Text(
                          serviceDetailsTabs.reverse[e] ?? "---",
                          style: context.titleSmall?.copyWith(
                              color: isSelected ? primaryColor : null),
                        ),
                      );
                    }).toList(),
                  ),
                );
              },
            ),
            PositionedDirectional(
              start: 0.0,
              end: 0.0,
              top: appBarSize > 0 ? appBarSize : 0,
              bottom: -100,
              child: Opacity(
                opacity: percent,
                child: Padding(
                  padding: EdgeInsets.symmetric(horizontal: 30 * percent),
                  child: const Card(
                    elevation: 20.0,
                    child: Center(
                      child: Text("Widget"),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  double get maxExtent => 44;

  @override
  double get minExtent => kToolbarHeight;

  @override
  bool shouldRebuild(SliverPersistentHeaderDelegate oldDelegate) {
    return true;
  }
}
