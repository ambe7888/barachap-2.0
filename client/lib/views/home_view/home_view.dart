// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/services/home_services/home_category_service.dart';
import 'package:prohandy_client/services/home_services/home_providers_service.dart';
import 'package:prohandy_client/services/home_services/home_slider_service.dart';
import 'package:prohandy_client/services/home_services/unread_count_service.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/view_models/home_view_model/home_view_model.dart';
import 'package:prohandy_client/views/home_view/components/home_app_bar.dart';
import 'package:prohandy_client/views/home_view/components/home_popular_services.dart';
import 'package:prohandy_client/views/home_view/components/home_slider.dart';
import 'package:provider/provider.dart';

import '../../services/home_services/home_featured_services_service.dart';
import '../../services/home_services/landing_offer_service.dart';
import 'components/home_categories.dart';
import 'components/home_category_services_list.dart';
import 'components/home_featured_services.dart';
import 'components/home_job_post_banner.dart';
import 'components/home_providers.dart';
import 'components/home_view_header.dart';

class HomeView extends StatelessWidget {
  const HomeView({super.key});

  @override
  Widget build(BuildContext context) {
    final hm = HomeViewModel.instance;
    UnreadCountService.instance.fetchUnreadCounts();
    LandingOfferService.instance.fetchPrimaryOfferContent(context);
    return CustomRefreshIndicator(
      onRefresh: () async {
        Provider.of<HomeSliderService>(context, listen: false)
            .fetchHomeSlider();
        Provider.of<HomeFeaturedServicesService>(context, listen: false)
            .fetchHomeFeaturedServices();
        Provider.of<HomeSliderService>(context, listen: false)
            .fetchHomeSlider();
        UnreadCountService.instance.fetchUnreadCounts();
        await Provider.of<HomeCategoryService>(context, listen: false)
            .fetchCategories();
        await Provider.of<HomeProvidersService>(context, listen: false)
            .fetchHomeProviders();
      },
      child: Column(
        children: [
          Expanded(
            child: SizedBox(
              height: context.height,
              child: CustomScrollView(
                controller: hm.scrollController,
                physics: const AlwaysScrollableScrollPhysics(),
                slivers: [
                  const SliverAppBar(
                    expandedHeight: 164,
                    pinned: true,
                    leadingWidth: 0,
                    title: HomeAppBar(),
                    flexibleSpace:
                        FlexibleSpaceBar(background: HomeViewHeader()),
                  ),
                  SliverList(
                      delegate: SliverChildListDelegate(
                    [
                      8.toHeight,
                      const HomeCategories(),
                      const HomeSlider(),
                      8.toHeight,
                      const HomeFeaturedServices(),
                      8.toHeight,
                      const HomeJobPostBanner(),
                      8.toHeight,
                      const HomeCategoryServicesScrolls(),
                      8.toHeight,
                    ],
                  ))
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class HomeCategoryServicesScrolls extends StatelessWidget {
  const HomeCategoryServicesScrolls({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<HomeCategoryService>(
      builder: (context, hc, child) {
        if (hc.categoryList == null || hc.categoryList!.isEmpty) {
          return const SizedBox();
        }
        return Column(
          children: hc.categoryList!.map((cat) {
            return HomeCategoryServicesList(category: cat);
          }).toList(),
        );
      },
    );
  }
}

