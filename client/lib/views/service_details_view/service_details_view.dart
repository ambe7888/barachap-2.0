import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/services/booking_services/booking_addons_service.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/service_booking_views/service_booking_addons_view.dart';
import 'package:prohandy_client/views/service_details_view/components/related_service_list.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_security.dart';
import 'package:prohandy_client/views/service_details_view/components/service_details_tabs.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../services/dynamics/cancellation_policy_service.dart';
import '../../services/home_services/service_details_service.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../utils/components/custom_refresh_indicator.dart';
import '../../view_models/service_booking_view_model/service_booking_view_model.dart';
import '../../view_models/service_details_view_model/service_details_view_model.dart';
import '../service_provider_view/components/provider_location_block.dart';
import 'components/service_details_basics.dart';
import 'components/service_details_cancellation_policy.dart';
import 'components/service_details_favorite_Icon.dart';
import 'components/service_details_images.dart';
import 'components/service_details_skeleton.dart';
import 'components/service_details_tabs_titles.dart';

class ServiceDetailsView extends StatelessWidget {
  final dynamic id;
  const ServiceDetailsView({super.key, this.id});

  @override
  Widget build(BuildContext context) {
    final sdProvider = Provider.of<ServiceDetailsService>(
      context,
      listen: false,
    );
    final sdm = ServiceDetailsViewModel.instance;
    final cps = CancellationPolicyService.instance;
    return Scaffold(
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await sdProvider.fetchServiceDetails(id);
        },
        child: CustomFutureWidget(
          function:
              sdProvider.shouldAutoFetch(id)
                  ? sdProvider.fetchServiceDetails(id)
                  : null,
          shimmer: const ServiceDetailsSkeleton(),
          child: Consumer<ServiceDetailsService>(
            builder: (context, sd, child) {
              final serviceDetails = sd.serviceDetailsModel(id);
              if (serviceDetails.allServices == null) {
                return Scaffold(
                  appBar: AppBar(
                    leading: const NavigationPopIcon(),
                    backgroundColor: context.color.accentContrastColor,
                  ),
                  body: EmptyWidget(
                    title: LocalKeys.serviceNotFound,
                    margin: const EdgeInsets.symmetric(vertical: 8),
                  ),
                );
              }
              return CustomScrollView(
                slivers: [
                  SliverAppBar(
                    backgroundColor: Colors.transparent,
                    surfaceTintColor: Colors.transparent,
                    pinned: true,
                    titleSpacing: 0,
                    leading: const NavigationPopIcon(),
                    actions: [
                      if ((sd.serviceDetailsModel(id).allServices?.videoId ??
                              "")
                          .isNotEmpty)
                        ValueListenableBuilder(
                          valueListenable: sdm.showVideo,
                          builder: (context, value, child) {
                            return GestureDetector(
                              onTap: () {
                                sdm.showVideo.value = !value;
                              },
                              child: Container(
                                alignment: Alignment.center,
                                padding: 6.paddingAll,
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  color: context.color.accentContrastColor,
                                ),
                                child: Icon(
                                  value
                                      ? Icons.image
                                      : Icons.ondemand_video_rounded,
                                  size: 20,
                                  color: context.color.tertiaryContrastColo,
                                ),
                              ),
                            );
                          },
                        ),
                      Padding(
                        padding: const EdgeInsets.all(8.0),
                        child: ServiceDetailsFavoriteIcon(id: id),
                      ),
                    ],
                    expandedHeight: 250,
                    flexibleSpace: ServiceDetailsImages(
                      videoId: sd.serviceDetailsModel(id).allServices?.videoId,
                      serviceDetails: sd.serviceDetailsModel(id),
                    ),
                  ),
                  ServiceDetailsBasics(
                    serviceDetails: sd.serviceDetailsModel(id),
                    id: id,
                  ).toSliver,
                  8.toHeight.toSliver,
                  const SliverAppBar(
                    titleSpacing: 0,
                    pinned: true,
                    primary: false,
                    leadingWidth: 0,
                    leading: SizedBox(),
                    title: ServiceDetailsTabsTitles(),
                    flexibleSpace: SizedBox(),
                  ),
                  ServiceDetailsTabs(
                    serviceDetails: sd.serviceDetailsModel(id),
                  ).toSliver,
                  if (serviceDetails
                              .allServices
                              ?.provider
                              ?.providerServiceArea
                              ?.latitude !=
                          null ||
                      serviceDetails
                              .allServices
                              ?.admin
                              ?.serviceArea
                              ?.latitude !=
                          null) ...[
                    8.toHeight.toSliver,
                    ProviderLocationBlock(
                      title:
                          sd.serviceDetailsModel(id).allServices?.title ?? "--",
                      lat:
                          serviceDetails
                              .allServices
                              ?.provider
                              ?.providerServiceArea
                              ?.latitude ??
                          serviceDetails
                              .allServices
                              ?.admin
                              ?.serviceArea
                              ?.latitude,
                      lng:
                          serviceDetails
                              .allServices
                              ?.provider
                              ?.providerServiceArea
                              ?.longitude ??
                          serviceDetails
                              .allServices
                              ?.admin
                              ?.serviceArea
                              ?.longitude,
                    ).toSliver,
                    8.toHeight.toSliver,
                  ],
                  if ((sd.serviceDetailsModel(id).relatedServices ?? [])
                      .isNotEmpty) ...[
                    8.toHeight.toSliver,
                    RelatedServiceList(
                      relatedServices:
                          sd.serviceDetailsModel(id).relatedServices!,
                    ).toSliver,
                  ],
                  if (1 == 2) ...[
                    8.toHeight.toSliver,
                    const ServiceDetailsSecurity().toSliver,
                  ],
                  if ((cps.cancellationData.value["description"]?.toString() ??
                          "")
                      .isNotEmpty) ...[
                    8.toHeight.toSliver,
                    ServiceDetailsCancellationPolicy(
                      description:
                          cps.cancellationData.value["description"] ?? "",
                    ).toSliver,
                  ],
                ],
              );
            },
          ),
        ),
      ),
      bottomNavigationBar: Consumer<ServiceDetailsService>(
        builder: (context, sd, child) {
          return sd.serviceDetailsModel(id).allServices == null
              ? const SizedBox()
              : Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 24,
                  vertical: 16,
                ),
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                  border: Border(
                    top: BorderSide(color: context.color.primaryBorderColor),
                  ),
                ),
                child: CustomButton(
                  onPressed: () {
                    ServiceBookingViewModel.dispose;
                    final svm = ServiceBookingViewModel.instance;
                    svm.selectedService.value =
                        sd.serviceDetailsModel(id).allServices!;
                    Provider.of<BookingAddonsService>(
                      context,
                      listen: false,
                    ).reset();
                    context.animateToPage(const ServiceBookingAddonsView());
                  },
                  btText: LocalKeys.bookService,
                ),
              );
        },
      ),
    );
  }
}
