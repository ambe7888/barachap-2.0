import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/service_services/service_list_service.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';
import 'package:prohand/view_models/my_services_view_model/my_services_view_model.dart';
import 'package:prohand/views/my_services_view/components/service_list_add_button.dart';
import 'package:provider/provider.dart';

import '../../helper/svg_assets.dart';
import 'components/my_service_tile.dart';
import 'components/service_list_skeleton.dart';

class MyServicesView extends StatefulWidget {
  const MyServicesView({super.key});

  @override
  State<MyServicesView> createState() => _MyServicesViewState();
}

class _MyServicesViewState extends State<MyServicesView> {
  bool alreadyAddedListener = false;
  @override
  Widget build(BuildContext context) {
    final msm = MyServicesViewModel.instance;
    if (!alreadyAddedListener) {
      alreadyAddedListener = true;
      msm.scrollController.addListener(() {
        msm.tryToLoadMore(context).then((_) {
          setState(() {});
        });
      });
    }
    return Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          title: const ServiceListAddButton(),
        ),
        body: CustomRefreshIndicator(
          refreshKey: msm.refreshKey,
          onRefresh: () async {
            final slProvider =
                Provider.of<ServiceListService>(context, listen: false);
            await slProvider.fetchServiceList(refresh: true);
          },
          child: Column(
            children: [
              Divider(
                height: 8,
                thickness: 8,
                color: context.color.backgroundColor,
              ),
              16.toHeight,
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Padding(
                    padding: 24.paddingH,
                    child: Row(
                      children: [
                        Expanded(
                            flex: 1,
                            child: TextFormField(
                              controller: msm.titleController,
                              textInputAction: TextInputAction.search,
                              decoration: InputDecoration(
                                hintText: LocalKeys.search,
                                prefixIcon: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    FittedBox(
                                      child: Row(
                                        children: [
                                          4.toWidth,
                                          SvgAssets.search.toSVGSized(24,
                                              color: context
                                                  .color.primaryContrastColor),
                                        ],
                                      ),
                                    )
                                  ],
                                ),
                              ),
                              onFieldSubmitted: (value) {
                                if ((value).trim().isNotEmpty) {
                                  setState(() {
                                    Provider.of<ServiceListService>(context,
                                            listen: false)
                                        .fetchServiceList()
                                        .then((_) {
                                      setState(() {});
                                    });
                                  });
                                }
                              },
                            )),
                      ],
                    ),
                  ),
                  12.toHeight,
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    padding: 24.paddingH,
                    child: Wrap(
                      spacing: 6,
                      children: msm.serviceStatusValues.keys.map((status) {
                        return ValueListenableBuilder(
                          valueListenable: msm.selectedStatus,
                          builder: (context, value, child) {
                            return _button(
                                title: status,
                                status: value,
                                isSelected: value == status,
                                onPressed: () {
                                  msm.selectedStatus.value = status;
                                  setState(() {
                                    Provider.of<ServiceListService>(context,
                                            listen: false)
                                        .fetchServiceList()
                                        .then((_) {
                                      setState(() {});
                                    });
                                  });
                                });
                          },
                        );
                      }).toList(),
                    ),
                  )
                ],
              ),
              Consumer<ServiceListService>(builder: (context, sl, child) {
                if ((sl.serviceListModel.allServices ?? []).isEmpty &&
                    !sl.isLoading &&
                    !sl.shouldAutoFetch) {
                  return Expanded(
                      child: EmptyWidget(title: LocalKeys.noServicesFound));
                }
                return FutureBuilder(
                  future: sl.shouldAutoFetch ? sl.fetchServiceList() : null,
                  builder: ((context, snap) {
                    if (sl.isLoading) {
                      return const ServiceListSkeleton();
                    }
                    return Expanded(
                      child: Scrollbar(
                          controller: msm.scrollController,
                          child: CustomScrollView(
                            controller: msm.scrollController,
                            physics: const AlwaysScrollableScrollPhysics(),
                            slivers: [
                              SliverList.separated(
                                itemBuilder: (context, index) {
                                  if (sl.nextPage != null &&
                                      (sl.serviceListModel.allServices ?? [])
                                              .length ==
                                          (index)) {
                                    return ScrollPreloader(
                                      loading: sl.nextPageLoading,
                                    );
                                  }
                                  final service =
                                      sl.serviceListModel.allServices![index];
                                  return MyServiceTile(
                                    service: service,
                                  );
                                },
                                separatorBuilder: (context, index) {
                                  final ind = index + 1;
                                  return (sl.nextPage != null &&
                                          (sl.serviceListModel.allServices ??
                                                      [])
                                                  .length ==
                                              (ind))
                                      ? const SizedBox()
                                      : 0.toWidth.divider.hp20;
                                },
                                itemCount: (sl.serviceListModel.allServices
                                            ?.length ??
                                        0) +
                                    (sl.nextPage != null && !sl.nexLoadingFailed
                                        ? 1
                                        : 0),
                              )
                            ],
                          )),
                    );
                  }),
                );
              }),
            ],
          ),
        ));
  }

  _button({
    required String title,
    required status,
    bool isSelected = false,
    onPressed,
  }) {
    return isSelected
        ? ElevatedButton(
            onPressed: () {},
            child: Text(title),
          )
        : OutlinedButton(
            onPressed: onPressed,
            child: Text(title),
          );
  }
}
