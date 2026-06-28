import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/service/favorite_services_service.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_refresh_indicator.dart';
import '../service_result_view/components/service_tile.dart';

class FavoriteServicesView extends StatelessWidget {
  const FavoriteServicesView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.favoriteServices),
      ),
      body: Consumer<FavoriteServicesService>(builder: (context, fj, child) {
        return CustomRefreshIndicator(
          onRefresh: () async {},
          child: Column(
            children: [
              const SizedBox().divider,
              Expanded(
                child: fj.favoriteList.isEmpty
                    ? EmptyWidget(title: LocalKeys.noFavoritesFound)
                    : Scrollbar(
                        child: CustomScrollView(
                        slivers: [
                          SliverList.separated(
                            itemBuilder: (context, index) {
                              final service = fj.services[index];
                              return ServiceTile(service: service);
                            },
                            separatorBuilder: (context, index) => Divider(
                              color: context.color.primaryBorderColor,
                              height: 2,
                            ).hp20,
                            itemCount: fj.services.length,
                          ),
                        ],
                      )),
              )
            ],
          ),
        );
      }),
    );
  }
}
