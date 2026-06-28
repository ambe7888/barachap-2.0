import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/booking_services/place_order_service.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/landding_view_model/landding_view_model.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';
import 'package:prohandy_client/views/order_summery_view/components/order_summary_cost_info.dart';
import 'package:prohandy_client/views/order_summery_view/components/order_summery_buttons.dart';
import 'package:prohandy_client/views/order_summery_view/components/order_summery_service.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../utils/components/custom_future_widget.dart';

class OrderSummeryView extends StatelessWidget {
  final Function(BuildContext context)? updateFunction;
  const OrderSummeryView({super.key, this.updateFunction});

  @override
  Widget build(BuildContext context) {
    final sbm = ServiceBookingViewModel.instance;

    return PopScope(
      canPop: false,
      onPopInvoked: (didPop) {
        if (sbm.paymentLoading.value) return;
        LandingViewModel.instance.navigateToLanding(context);
      },
      child: FutureBuilder(
        future: updateFunction != null ? updateFunction!(context) : null,
        builder: (context, snap) {
          return Scaffold(
            appBar: AppBar(
              leading: NavigationPopIcon(
                onTap: () {
                  if (sbm.paymentLoading.value) return;
                  LandingViewModel.instance.navigateToLanding(context);
                },
              ),
              title: Text(LocalKeys.orderSummery),
            ),
            body: ValueListenableBuilder(
              valueListenable: sbm.paymentLoading,
              builder: (context, pLoading, child) {
                return CustomFutureWidget(
                  function: updateFunction != null ? null : null,
                  isLoading: false,
                  child: Stack(
                    children: [
                      Consumer<PlaceOrderService>(
                        builder: (context, po, child) {
                          return Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 24,
                                  vertical: 16,
                                ),
                                color: context.color.accentContrastColor,
                                child: Column(
                                  children: [
                                    SvgAssets.addFilled.toSVGSized(
                                      80,
                                      color: context.color.primarySuccessColor,
                                    ),
                                    Text(
                                      LocalKeys.bookingSuccessful,
                                      style: context.titleLarge?.bold,
                                    ),
                                    const Row(),
                                  ],
                                ),
                              ),
                              SvgPicture.asset(
                                "assets/icons/top.svg",
                                width: context.width,
                                color: context.color.accentContrastColor,
                              ),
                              Expanded(
                                child: SingleChildScrollView(
                                  child: Column(
                                    children: [
                                      OrderSummeryService(po: po),
                                      Divider(
                                        height: 16,
                                        color: context.color.primaryBorderColor,
                                      ),
                                      OrderSummaryCostInfo(po: po),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          );
                        },
                      ),
                      if (pLoading)
                        Container(
                          height: double.infinity,
                          width: double.infinity,
                          color: context.color.accentContrastColor.withOpacity(
                            .7,
                          ),
                          child: const Center(child: CustomPreloader()),
                        ),
                    ],
                  ),
                );
              },
            ),
            bottomNavigationBar: const OrderSummeryButtons(),
          );
        },
      ),
    );
  }
}
