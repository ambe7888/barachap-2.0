import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';
import 'package:prohand/view_models/service_result_view_model/service_result_view_model.dart';

import '../../models/service_models/service_model.dart';
import 'components/service_result_search_bar.dart';
import 'components/service_tile.dart';

class ServiceResultView extends StatelessWidget {
  static const routeName = "service_result_view";
  const ServiceResultView({super.key});

  @override
  Widget build(BuildContext context) {
    final srm = ServiceResultViewModel.instance;
    srm.scrollController.addListener(() {});
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.results),
      ),
      backgroundColor: context.color.accentContrastColor,
      body: Scrollbar(
        controller: srm.scrollController,
        child: CustomScrollView(
          controller: srm.scrollController,
          slivers: [
            const SliverAppBar(
              floating: true,
              snap: true,
              leadingWidth: 0,
              leading: SizedBox(),
              title: ResultViwSearchBar(),
            ),
            SliverList.separated(
              itemBuilder: (context, index) {
                final service = Service(
                    id: index,
                    title: "Home Cleaning Services at Miami, FL at Miami, FL ",
                    price: 266,
                    image:
                        "https://i.postimg.cc/tCY4JbQq/order-attachment-1716468898.jpg",
                    discountPrice: 199,
                    providerName: "John Doe",
                    providerImage: "https://i.postimg.cc/y8nKyrzQ/ML1.png",
                    category: "Painting",
                    isFavorite: false,
                    avgRating: 4.5,
                    unit: "1 hr");
                return ServiceTile(service: service);
              },
              separatorBuilder: (context, index) => Divider(
                color: context.color.primaryBorderColor,
                height: 2,
              ).hp20,
              itemCount: 20,
            ),
            SliverList.list(children: const [
              ScrollPreloader(
                loading: false,
              ),
            ]),
          ],
        ),
      ),
    );
  }
}
