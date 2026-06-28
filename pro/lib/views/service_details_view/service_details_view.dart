import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:prohand/view_models/service_details_view_model/service_details_view_model.dart';
import 'package:prohand/views/add_edit_service_view/add_edit_service_view.dart';
import 'package:prohand/views/service_details_view/components/service_details_security.dart';
import 'package:prohand/views/service_details_view/components/service_details_tabs.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../helper/svg_assets.dart';
import '../../utils/components/custom_button.dart';
import '../../utils/components/custom_squircle_widget.dart';
import 'components/service_details_basics.dart';
import 'components/service_details_cancellation_policy.dart';
import 'components/service_details_images.dart';
import 'components/service_details_skeleton.dart';
import 'components/service_details_tabs_titles.dart';

class ServiceDetailsView extends StatelessWidget {
  final dynamic id;
  const ServiceDetailsView({super.key, required this.id});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceDetailsService>(builder: (context, sd, child) {
      return Scaffold(
        body: CustomRefreshIndicator(
          onRefresh: () async {
            await sd.fetchServiceDetails(id);
          },
          child: CustomFutureWidget(
            function:
                sd.shouldAutoFetch(id) ? sd.fetchServiceDetails(id) : null,
            shimmer: const ServiceDetailsSkeleton(),
            child:
                Consumer<ServiceDetailsService>(builder: (context, sd, child) {
              if (sd.serviceDetailsModel.allServices == null) {
                return Scaffold(
                    backgroundColor: context.color.accentContrastColor,
                    appBar: AppBar(
                      leading: const NavigationPopIcon(),
                      backgroundColor: Colors.transparent,
                    ),
                    body: EmptyWidget(title: LocalKeys.serviceNotFound));
              }
              return CustomScrollView(
                slivers: [
                  SliverAppBar(
                    backgroundColor: Colors.transparent,
                    surfaceTintColor: Colors.transparent,
                    pinned: true,
                    titleSpacing: 0,
                    leading: const NavigationPopIcon(),
                    expandedHeight: 250,
                    flexibleSpace: sd.serviceDetailsModel.allServices == null
                        ? null
                        : ServiceDetailsImages(
                            sd: sd,
                          ),
                  ),
                  ServiceDetailsBasics(
                    sd: sd,
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
                  const ServiceDetailsTabs().toSliver,
                  8.toHeight.toSliver,
                  if (1 == 2) ...[
                    const ServiceDetailsSecurity().toSliver,
                    8.toHeight.toSliver,
                    const ServiceDetailsCancellationPolicy().toSliver
                  ],
                ],
              );
            }),
          ),
        ),
        bottomNavigationBar: sd.serviceDetailsModel.allServices == null
            ? null
            : Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                decoration: BoxDecoration(
                    color: context.color.accentContrastColor,
                    border: Border(
                        top: BorderSide(
                            color: context.color.primaryBorderColor))),
                child: Row(
                  children: [
                    Expanded(
                      flex: 1,
                      child: CustomButton(
                          onPressed: () {
                            AddEditServiceViewModel.dispose;
                            AddEditServiceViewModel.instance.initEdit(context);
                            context.toPage(const AddEditServiceView());
                          },
                          btText: LocalKeys.editService),
                    ),
                    12.toWidth,
                    GestureDetector(
                      onTap: () {
                        final jdm = ServiceDetailsViewModel.instance;
                        jdm.tryDeletingService(context);
                      },
                      child: SquircleContainer(
                        color: context.color.primaryWarningColor,
                        radius: 12,
                        padding: EdgeInsets.all(8),
                        child: SvgAssets.trash.toSVGSized(
                          24,
                          color: context.color.accentContrastColor,
                        ),
                      ),
                    )
                  ],
                ),
              ),
      );
    });
  }
}
