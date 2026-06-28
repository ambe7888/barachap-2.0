import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/service_provider_view/components/service_provider_about.dart';
import 'package:prohand/views/service_provider_view/components/staff_tile.dart';
import 'package:prohand/views/service_result_view/components/service_tile.dart';

import '../../models/provider_model.dart';
import '../../models/service_models/service_model.dart';
import '../../view_models/service_provider_view_model/service_provider_view_model.dart';
import '../service_details_view/components/service_details_provider.dart';
import 'components/service_provider_tab_titles.dart';

class ServiceProviderView extends StatelessWidget {
  const ServiceProviderView({super.key});

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.serviceProvider),
      ),
      body: Scrollbar(
        child: ValueListenableBuilder(
          valueListenable: spm.selectedTab,
          builder: (context, value, child) {
            return CustomScrollView(
              slivers: [
                8.toHeight.toSliver,
                Container(
                  color: context.color.accentContrastColor,
                  padding:
                      const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                  child: ServiceDetailsProvider(
                      provider: ProviderModel(
                    name: "Rober Fox",
                    avgRating: 2.7,
                    id: 23,
                    orderCompleted: 12,
                    jobCompleted: 11,
                    completionRate: 77,
                    profession: "Cleanings",
                  )),
                ).toSliver,
                8.toHeight.toSliver,
                const SliverAppBar(
                  titleSpacing: 0,
                  pinned: true,
                  primary: false,
                  leadingWidth: 0,
                  leading: SizedBox(),
                  flexibleSpace: SizedBox(),
                  title: ServiceProviderTabTitles(),
                ),
                if (value == ServiceProviderTabsTypes.about)
                  const ServiceProviderAbout().toSliver,
                if (value == ServiceProviderTabsTypes.services)
                  SliverList.separated(
                    itemBuilder: (context, index) {
                      final service = Service(
                          id: index,
                          title:
                              "Home Cleaning Services at Miami, FL at Miami, FL ",
                          price: 266,
                          image:
                              "https://i.postimg.cc/tCY4JbQq/order-attachment-1716468898.jpg",
                          discountPrice: 199,
                          providerName: "John Doe",
                          providerImage:
                              "https://i.postimg.cc/y8nKyrzQ/ML1.png",
                          category: "Painting",
                          isFavorite: false,
                          avgRating: 4.5,
                          unit: "1 hr");
                      return Container(
                          color: context.color.accentContrastColor,
                          child: ServiceTile(service: service));
                    },
                    separatorBuilder: (context, index) =>
                        const SizedBox().divider,
                    itemCount: 5,
                  ),
                if (value == ServiceProviderTabsTypes.staffs)
                  SliverList.separated(
                    itemBuilder: (context, index) {
                      return StaffTile(
                        name: "Steave Jobbs",
                        createdAt: DateTime.now(),
                        imageUrl:
                            "https://i.postimg.cc/6qrr761Z/handywomenjpeg.jpg",
                      );
                    },
                    separatorBuilder: (context, index) =>
                        const SizedBox().divider,
                    itemCount: 5,
                  ),
                1200.toHeight.toSliver,
              ],
            );
          },
        ),
      ),
    );
  }
}
