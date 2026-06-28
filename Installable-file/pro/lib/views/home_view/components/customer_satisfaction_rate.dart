import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/profile_services/dashboard_info_service.dart';
import '../../../services/theme_service.dart';
import '../../../utils/components/alerts.dart';
import '../../../utils/components/text_skeleton.dart';

class CustomerSatisfactionRate extends StatelessWidget {
  const CustomerSatisfactionRate({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
      return Consumer<DashboardInfoService>(builder: (context, di, child) {
        final pct =
            ((di.dashboardInfoModel?.customerSatisfactionRate ?? 0) / 100);
        return Container(
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: di.isLoading
              ? Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const TextSkeleton(
                      height: 20,
                      width: 120,
                    ),
                    12.toHeight,
                    SquircleContainer(
                        width: double.infinity,
                        radius: 10,
                        color: context.color.mutedPendingColor,
                        height: 36,
                        child: const Row(
                          children: [],
                        )),
                  ],
                ).shim
              : Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          flex: 1,
                          child: Text(
                            LocalKeys.customerSatisfaction,
                            style: context.headlineLarge?.bold,
                          ),
                        ),
                        IconButton(
                          onPressed: () {
                            Alerts().showInfoDialogue(
                              context: context,
                              title: LocalKeys.orderCompletion,
                              description: LocalKeys
                                  .overallCustomerSatisfactionsFromYourRatings,
                            );
                          },
                          icon: const Icon(Icons.info_outline_rounded),
                          color: context.color.tertiaryContrastColo,
                        )
                      ],
                    ),
                    12.toHeight,
                    SquircleContainer(
                        width: double.infinity,
                        radius: 10,
                        color: context.color.mutedPendingColor,
                        child: Row(
                          children: [
                            SquircleContainer(
                                color: context.color.primaryPendingColor,
                                radius: 10,
                                padding: 6.paddingAll,
                                width: (context.width - 48) *
                                    (pct < .13 ? .13 : pct),
                                child: Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: [
                                    Text(
                                      "${(di.dashboardInfoModel?.customerSatisfactionRate ?? 0).round()}%",
                                      style: context.titleSmall?.bold.copyWith(
                                        color:
                                            context.color.accentContrastColor,
                                      ),
                                    )
                                  ],
                                )),
                          ],
                        )),
                  ],
                ),
        );
      });
    });
  }
}
