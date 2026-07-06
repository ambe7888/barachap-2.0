import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/empty_widget.dart';
import 'package:prohand/utils/components/scrolling_preloader.dart';
import 'package:prohand/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:provider/provider.dart';

import '../../services/order_services/order_list_service.dart';
import '../../view_models/order_list_view_model/order_status_enums.dart';
import 'components/order_list_filter_sheet.dart';
import 'components/order_list_skeleton.dart';
import 'components/order_list_tile.dart';

class OrderListView extends StatelessWidget {
  const OrderListView({super.key});

  @override
  Widget build(BuildContext context) {
    final olm = OrderListViewModel.instance;
    olm.scrollController.addListener(() {
      olm.tryToLoadMore(context);
    });
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        title: Text(LocalKeys.myOrders),
        actions: [
          GestureDetector(
            onTap: () {
              final olm = OrderListViewModel.instance;
              final ValueNotifier<BookingStatus?> bookingStatus =
                  ValueNotifier(olm.bookingStatus.value);
              final ValueNotifier<PaymentStatus?> paymentStatus =
                  ValueNotifier(olm.paymentStatus.value);
              showModalBottomSheet(
                context: context,
                isScrollControlled: true,
                backgroundColor: context.color.accentContrastColor,
                builder: (context) {
                  return OrderListFilterSheet(
                    bookingStatus: bookingStatus,
                    paymentStatus: paymentStatus,
                  );
                },
              );
            },
            child: Container(
              padding: 12.paddingH,
              color: Colors.transparent,
              child: SvgAssets.filter.toSVGSized(
                24,
                color: context.color.secondaryContrastColor,
              ),
            ),
          )
        ],
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await Provider.of<OrderListService>(context, listen: false)
              .fetchOrderList();
        },
        child: Scrollbar(
          controller: olm.scrollController,
          child: Consumer<OrderListService>(builder: (context, ol, child) {
            return CustomFutureWidget(
              function: ol.shouldAutoFetch ? ol.fetchOrderList() : null,
              isLoading: ol.isLoading,
              shimmer: const OrderListSkeleton(),
              child: ValueListenableBuilder(
                  valueListenable: olm.searchQuery,
                  builder: (context, query, child) {
                    return ValueListenableBuilder(
                        valueListenable: olm.statusType,
                        builder: (context, value, child) {
                          final filteredOrders = ol.myOrdersModel.allOrders
                              .where((o) =>
                                  query.isEmpty ||
                                  (o.id
                                          ?.toString()
                                          .contains(query.toLowerCase()) ==
                                      true) ||
                                  (o.subOrderLocations?.address
                                          ?.toLowerCase()
                                          .contains(query.toLowerCase()) ==
                                      true))
                              .toList();

                          if (filteredOrders.isEmpty) {
                            return Column(
                              children: [
                                Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 24.0, vertical: 8.0),
                                  child: TextFormField(
                                    decoration: InputDecoration(
                                      hintText: LocalKeys.search,
                                      prefixIcon: const Icon(Icons.search),
                                      border: OutlineInputBorder(
                                        borderRadius: BorderRadius.circular(8),
                                      ),
                                      contentPadding:
                                          const EdgeInsets.symmetric(vertical: 0),
                                    ),
                                    onChanged: (v) =>
                                        olm.searchQuery.value = v,
                                  ),
                                ),
                                Expanded(
                                  child: EmptyWidget(
                                      title: LocalKeys.noOrdersFound),
                                ),
                              ],
                            );
                          }

                          return Column(
                            children: [
                              Padding(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 24.0, vertical: 8.0),
                                child: TextFormField(
                                  decoration: InputDecoration(
                                    hintText: LocalKeys.search,
                                    prefixIcon: const Icon(Icons.search),
                                    border: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    contentPadding:
                                        const EdgeInsets.symmetric(vertical: 0),
                                  ),
                                  onChanged: (v) =>
                                      olm.searchQuery.value = v,
                                ),
                              ),
                              Expanded(
                                child: CustomScrollView(
                                  controller: olm.scrollController,
                                  physics: const AlwaysScrollableScrollPhysics(),
                                  slivers: [
                                    12.toHeight.toSliver,
                                    SliverList.separated(
                                      itemBuilder: (context, index) {
                                        final order = filteredOrders[index];
                                        return OrderListTile(
                                          order: order,
                                        );
                                      },
                                      separatorBuilder: (context, index) =>
                                          16.toHeight,
                                      itemCount: filteredOrders.length,
                                    ),
                                    24.toHeight.toSliver,
                                    if (ol.nextPage != null &&
                                        !ol.nexLoadingFailed)
                                      ScrollPreloader(
                                              loading: ol.nextPageLoading)
                                          .toSliver,
                                    24.toHeight.toSliver,
                                  ],
                                ),
                              ),
                            ],
                          );
                        });
                  }),
            );
          }),
        ),
      ),
    );
  }
}

class CustomSliverHeaderDelegate extends SliverPersistentHeaderDelegate {
  @override
  final double minExtent;
  @override
  final double maxExtent;
  final Widget child;

  CustomSliverHeaderDelegate({
    required this.minExtent,
    required this.maxExtent,
    required this.child,
  });

  @override
  Widget build(
      BuildContext context, double shrinkOffset, bool overlapsContent) {
    return SizedBox.expand(child: child);
  }

  @override
  bool shouldRebuild(CustomSliverHeaderDelegate oldDelegate) {
    return maxExtent != oldDelegate.maxExtent ||
        minExtent != oldDelegate.minExtent ||
        child != oldDelegate.child;
  }
}
