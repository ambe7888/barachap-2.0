import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:prohand/views/service_details_view/components/service_details_addons.dart';
import 'package:prohand/views/service_details_view/components/service_details_description.dart';
import 'package:prohand/views/service_details_view/components/service_details_faq_tab.dart';
import 'package:prohand/views/service_details_view/components/service_details_staffs.dart';
import 'package:provider/provider.dart';

import '../../../services/service_services/service_details_service.dart';
import 'service_details_excludes.dart';
import 'service_details_offers.dart';
import 'service_details_review_tab.dart';

class ServiceDetailsTabs extends StatelessWidget {
  const ServiceDetailsTabs({super.key});

  @override
  Widget build(BuildContext context) {
    final sdm = ServiceDetailsViewModel.instance;
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      final offers = sd.serviceDetailsModel.allServices?.offers ?? [];
      final excludes = sd.serviceDetailsModel.allServices?.excludes ?? [];
      return ValueListenableBuilder(
        valueListenable: sdm.selectedTab,
        builder: (context, value, child) {
          return Column(
            children: [
              Container(
                width: double.infinity,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                color: context.color.accentContrastColor,
                child: widget,
              ),
              if (sdm.selectedTab.value ==
                  ServiceDetailsTabsTypes.overview) ...[
                ServiceDetailsOffers(offers: offers),
                ServiceDetailsExcludes(
                  excludes: excludes,
                ),
              ]
            ],
          );
        },
      );
    });
  }

  Widget get widget {
    final sdm = ServiceDetailsViewModel.instance;
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.faq) {
      return const ServiceDetailsFaqTab();
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.addons) {
      return const ServiceDetailsAddons();
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.reviews) {
      return const ServiceDetailsReviewTab();
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.staffs) {
      return const ServiceDetailsStaffs();
    }
    return const ServiceDetailsDescription();
  }
}
