import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/utils/components/scrolling_preloader.dart';
import 'package:prohandy_client/view_models/service_result_view_model/service_result_view_model.dart';
import 'package:provider/provider.dart';

import '../../helper/svg_assets.dart';
import '../../services/service/service_by_category_service.dart';
import '../../utils/components/empty_widget.dart';
import '../service_result_view/components/service_result_skeleton.dart';
import '../service_result_view/components/service_tile.dart';
import '../service_result_view/service_list_map_view.dart';

class ServiceByCategoryView extends StatelessWidget {
  final dynamic catId;
  final String? catName;

  const ServiceByCategoryView({super.key, required this.catId, this.catName});

  @override
  Widget build(BuildContext context) {
    final srm = ServiceResultViewModel.instance;
    final ssProvider =
        Provider.of<ServiceByCategoryService>(context, listen: false);
    srm.scrollController.addListener(() {
      srm.tryToLoadMore(context);
    });
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.back),
        actions: [
          Consumer<ServiceByCategoryService>(builder: (context, ss, child) {
            if (ss.serviceByCategoryModel.allServices.isEmpty) {
              return SizedBox();
            }
            if (ss.serviceByCategoryModel.allServices
                .where((s) =>
                    (s.provider?.latitude != null) ||
                    (s.admin?.serviceArea?.latitude != null))
                .isEmpty) {
              return SizedBox();
            }
            return Padding(
              padding: const EdgeInsets.symmetric(horizontal: 10),
              child: IconButton(
                  icon: SvgAssets.map.toSVGSized(22,
                      color: context.color.secondaryContrastColor),
                  onPressed: () {
                    context.toPage(ServiceListMapView(
                        serviceList: ss.serviceByCategoryModel.allServices));
                  }),
            );
          })
        ],
      ),
      backgroundColor: context.color.accentContrastColor,
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await ssProvider.fetchServices(refreshing: true, catId: catId);
        },
        child:
            Consumer<ServiceByCategoryService>(builder: (context, sc, child) {
          return CustomFutureWidget(
            function: sc.shouldAutoFetch(catId)
                ? sc.fetchServices(catId: catId)
                : null,
            shimmer: const ServiceResultSkeleton(),
            child: Scrollbar(
              controller: srm.scrollController,
              child: CustomScrollView(
                controller: srm.scrollController,
                physics: const AlwaysScrollableScrollPhysics(),
                slivers: [
                  if (sc.serviceByCategoryModel.allServices.isEmpty)
                    SizedBox(
                            height: context.height * .8,
                            child:
                                EmptyWidget(title: LocalKeys.serviceNotFound))
                        .toSliver,
                  SliverList.separated(
                    itemBuilder: (context, index) {
                      final service =
                          sc.serviceByCategoryModel.allServices[index];
                      return ServiceTile(service: service);
                    },
                    separatorBuilder: (context, index) => Divider(
                      color: context.color.primaryBorderColor,
                      height: 2,
                    ).hp20,
                    itemCount: sc.serviceByCategoryModel.allServices.length,
                  ),
                  if (sc.nextPage != null && !sc.nexLoadingFailed)
                    SliverList.list(children: const [
                      ScrollPreloader(
                        loading: false,
                      ),
                    ]),
                ],
              ),
            ),
          );
        }),
      ),
    );
  }
}
