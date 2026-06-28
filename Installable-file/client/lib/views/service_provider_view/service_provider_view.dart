import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/service/provider_details_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/empty_element.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/service_provider_view/components/service_provider_about.dart';
import 'package:prohandy_client/views/service_provider_view/components/staff_tile.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_refresh_indicator.dart';
import '../../utils/components/field_label.dart';
import '../../view_models/service_provider_view_model/service_provider_view_model.dart';
import '../ratings_and_review_view/components/rating_tile.dart';
import '../service_result_view/components/service_tile.dart';
import 'components/provider_details_name_tile.dart';
import 'components/provider_details_skeleton.dart';
import 'components/provider_location_block.dart';
import 'components/provider_store_images.dart';
import 'components/service_provider_tab_titles.dart';

class ServiceProviderView extends StatelessWidget {
  final dynamic providerID;
  const ServiceProviderView({
    super.key,
    required this.providerID,
  });

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    final pdProvider =
        Provider.of<ProviderDetailsService>(context, listen: false);
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.serviceProvider),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await pdProvider.fetchProviderDetails(
              providerId: providerID.toString());
        },
        child: CustomFutureWidget(
          function: pdProvider.shouldAutoFetch(providerID.toString())
              ? pdProvider.fetchProviderDetails(
                  providerId: providerID.toString())
              : null,
          shimmer: const ProviderDetailsSkeleton(),
          child:
              Consumer<ProviderDetailsService>(builder: (context, pd, child) {
            if (pd.providerDetailsModel(providerID).userDetails == null) {
              return EmptyWidget(
                title: LocalKeys.providerNotFound,
                margin: const EdgeInsets.symmetric(vertical: 8),
              );
            }
            return Scrollbar(
              child: ValueListenableBuilder(
                valueListenable: spm.selectedTab,
                builder: (context, value, child) {
                  return CustomScrollView(
                    slivers: [
                      if ((pd
                                      .providerDetailsModel(providerID)
                                      .userDetails
                                      ?.storeImage ??
                                  [])
                              .isNotEmpty &&
                          pd
                                  .providerDetailsModel(providerID)
                                  .userDetails
                                  ?.videoUrl !=
                              null)
                        ProviderStoreImages(
                          videoUrl: pd
                              .providerDetailsModel(providerID)
                              .userDetails
                              ?.videoUrl,
                          images: pd
                              .providerDetailsModel(providerID)
                              .userDetails!
                              .storeImage!
                              .map((i) => i.toString())
                              .toList(),
                        ).toSliver,
                      8.toHeight.toSliver,
                      Container(
                        color: context.color.accentContrastColor,
                        padding: const EdgeInsets.symmetric(
                            horizontal: 24, vertical: 16),
                        child: OfferDetailsProviderTile(
                            provider: pd
                                .providerDetailsModel(providerID)
                                .userDetails!),
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
                        ServiceProviderAbout(
                                userDetails: pd
                                    .providerDetailsModel(providerID)
                                    .userDetails!)
                            .toSliver,
                      if (value == ServiceProviderTabsTypes.services) ...[
                        if (pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .services
                                ?.isEmpty ??
                            true)
                          EmptyElement(text: LocalKeys.noServiceFound).toSliver,
                        SliverList.separated(
                          itemBuilder: (context, index) {
                            final service = pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .services![index];
                            return Container(
                                color: context.color.accentContrastColor,
                                child: ServiceTile(service: service));
                          },
                          separatorBuilder: (context, index) =>
                              const SizedBox().divider,
                          itemCount: pd
                                  .providerDetailsModel(providerID)
                                  .userDetails!
                                  .services
                                  ?.length ??
                              0,
                        )
                      ],
                      if (value == ServiceProviderTabsTypes.about) ...[
                        if (pd
                                .providerDetailsModel(providerID)
                                .userDetails
                                ?.providerServiceArea
                                ?.latitude !=
                            null) ...[
                          ProviderLocationBlock(
                            title: pd
                                    .providerDetailsModel(providerID)
                                    .userDetails
                                    ?.fullName ??
                                "--",
                            lat: pd
                                .providerDetailsModel(providerID)
                                .userDetails
                                ?.providerServiceArea
                                ?.latitude,
                            lng: pd
                                .providerDetailsModel(providerID)
                                .userDetails
                                ?.providerServiceArea
                                ?.longitude,
                          ).toSliver,
                          8.toHeight.toSliver
                        ],
                        Container(
                                color: context.color.accentContrastColor,
                                width: double.infinity,
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 24, vertical: 16),
                                child: FieldLabel(label: LocalKeys.staffs))
                            .divider
                            .toSliver,
                        if (pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .providerStaffs
                                ?.isEmpty ??
                            true)
                          Container(
                                  color: context.color.accentContrastColor,
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 24, vertical: 16),
                                  child: EmptyElement(
                                      text: LocalKeys.noStaffAdded))
                              .toSliver,
                        SliverList.separated(
                          itemBuilder: (context, index) {
                            final staff = pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .providerStaffs![index];
                            return StaffTile(
                              name: staff.fullname ?? "---",
                              imageUrl: staff.image,
                            );
                          },
                          separatorBuilder: (context, index) =>
                              const SizedBox().divider,
                          itemCount: pd
                                  .providerDetailsModel(providerID)
                                  .userDetails
                                  ?.providerStaffs
                                  ?.length ??
                              0,
                        )
                      ],
                      if (value == ServiceProviderTabsTypes.reviews) ...[
                        if (pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .reviews
                                ?.isEmpty ??
                            true)
                          EmptyElement(text: LocalKeys.noStaffAdded).toSliver,
                        SliverList.separated(
                          itemBuilder: (context, index) {
                            final review = pd
                                .providerDetailsModel(providerID)
                                .userDetails!
                                .reviews![index];
                            return RatingTile(
                              userImage: review.reviewer?.image ?? "",
                              userName: review.reviewer?.name,
                              rating: review.rating,
                              description: review.message,
                              createdAt: review.createdAt,
                            );
                          },
                          separatorBuilder: (context, index) =>
                              const SizedBox().divider,
                          itemCount: pd
                                  .providerDetailsModel(providerID)
                                  .userDetails
                                  ?.reviews
                                  ?.length ??
                              0,
                        )
                      ],
                    ],
                  );
                },
              ),
            );
          }),
        ),
      ),
    );
  }
}
