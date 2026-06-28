import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_addons.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_description.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_faq_tab.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_staffs.dart';

import '../../../models/service/service_details_model.dart';
import 'service_details_excludes.dart';
import 'service_details_offers.dart';
import 'service_details_review_tab.dart';

class ServiceDetailsTabs extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;
  const ServiceDetailsTabs({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    final sdm = ServiceDetailsViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: sdm.selectedTab,
      builder: (context, value, child) {
        return Column(
          children: [
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: widget,
            ),
            if (sdm.selectedTab.value == ServiceDetailsTabsTypes.overview) ...[
              ServiceDetailsOffers(
                  offers: serviceDetails.allServices?.offers ?? []),
              ServiceDetailsExcludes(
                excludes: serviceDetails.allServices?.excludes ?? [],
              ),
            ]
          ],
        );
      },
    );
  }

  Widget get widget {
    final sdm = ServiceDetailsViewModel.instance;
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.faq) {
      return ServiceDetailsFaqTab(serviceDetails: serviceDetails);
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.reviews) {
      return ServiceDetailsReviewTab(serviceDetails: serviceDetails);
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.addons) {
      return ServiceDetailsAddons(serviceDetails: serviceDetails);
    }
    if (sdm.selectedTab.value == ServiceDetailsTabsTypes.staffs) {
      return ServiceDetailsStaffs(serviceDetails: serviceDetails);
    }
    return ServiceDetailsDescription(serviceDetails: serviceDetails);
  }
}
