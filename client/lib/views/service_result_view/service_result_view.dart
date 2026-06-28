import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/service/services_search_service.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/utils/components/scrolling_preloader.dart';
import 'package:prohandy_client/view_models/service_result_view_model/service_result_view_model.dart';
import 'package:prohandy_client/views/service_result_view/service_list_map_view.dart';
import 'package:provider/provider.dart';

import '../../utils/components/empty_widget.dart';
import 'components/service_result_search_bar.dart';
import 'components/service_result_skeleton.dart';
import 'components/service_tile.dart';

class ServiceResultView extends StatelessWidget {
  static const routeName = "service_result_view";
  const ServiceResultView({super.key});

  @override
  Widget build(BuildContext context) {
    final srm = ServiceResultViewModel.instance;
    final ssProvider =
        Provider.of<ServicesSearchService>(context, listen: false);
    srm.scrollController.addListener(() {
      srm.tryToLoadMore(context);
    });
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.results),
        actions: [
          Consumer<ServicesSearchService>(builder: (context, ss, child) {
            if (ss.searchResultModel.allServices.isEmpty) {
              return SizedBox();
            }
            if (ss.searchResultModel.allServices
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
                        serviceList: ss.searchResultModel.allServices));
                  }),
            );
          })
        ],
      ),
      backgroundColor: context.color.backgroundColor,
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await ssProvider.fetchHomeFeaturedServices(refreshing: true);
        },
        child: Consumer<ServicesSearchService>(builder: (context, ss, child) {
          return Scrollbar(
            controller: srm.scrollController,
            child: CustomScrollView(
              controller: srm.scrollController,
              physics: ss.isLoading
                  ? const NeverScrollableScrollPhysics()
                  : const AlwaysScrollableScrollPhysics(),
              slivers: [
                const SliverAppBar(
                  floating: true,
                  snap: true,
                  leadingWidth: 0,
                  leading: SizedBox(),
                  title: ResultViwSearchBar(),
                ),
                if (ss.isLoading) const ServiceResultSkeleton().toSliver,
                if (ss.searchResultModel.allServices.isEmpty)
                  SizedBox(
                          height: context.height * .8,
                          child: EmptyWidget(title: LocalKeys.serviceNotFound))
                      .toSliver,
                if (!ss.isLoading)
                  SliverList.separated(
                    itemBuilder: (context, index) {
                      final service = ss.searchResultModel.allServices[index];
                      return ServiceTile(service: service);
                    },
                    separatorBuilder: (context, index) => const SizedBox(),
                    itemCount: ss.searchResultModel.allServices.length,
                  ),
                if (ss.nextPage != null &&
                    !ss.nexLoadingFailed &&
                    !ss.isLoading)
                  SliverList.list(children: [
                    ScrollPreloader(
                      loading: ss.nextPageLoading,
                    ),
                  ]),
              ],
            ),
          );
        }),
      ),
    );
  }
}
