import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/payment/payment_gateway_service.dart';
import 'package:prohandy_client/services/service/cart_service.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/service_booking_view_model.dart';
import 'package:prohandy_client/views/payment_views/payment_gateways.dart';
import 'package:provider/provider.dart';

import '../sign_up_view/components/accepted_agreement.dart';

class BookingPaymentChooseView extends StatelessWidget {
  final bool payAgain;
  const BookingPaymentChooseView({super.key, this.payAgain = false});

  @override
  Widget build(BuildContext context) {
    final sbm = ServiceBookingViewModel.instance;
    final csProvider = Provider.of<CartService>(context, listen: false);
    return ChangeNotifierProvider(
      create: (context) => PaymentGatewayService(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.payment),
        ),
        body: Consumer<PaymentGatewayService>(builder: (context, pg, child) {
          return CustomRefreshIndicator(
            onRefresh: () async {
              await pg.fetchGateways(refresh: true);
            },
            child: Scrollbar(
                child: SingleChildScrollView(
              padding: 24.paddingH,
              physics: const AlwaysScrollableScrollPhysics(),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  0.toHeight.divider,
                  16.toHeight,
                  Text(
                    LocalKeys.choosePaymentMethod,
                    style: context.headlineLarge?.bold,
                  ),
                  24.toHeight,
                  PaymentGateways(
                    gatewayNotifier: sbm.selectedGateway,
                    attachmentNotifier: sbm.manualPaymentImage,
                    cardController: sbm.aCardController,
                    secretCodeController: sbm.authCodeController,
                    zUsernameController: sbm.zUsernameController,
                    expireDateNotifier: sbm.authNetExpireDate,
                    usernameController: TextEditingController(),
                  ),
                  24.toHeight,
                  const SizedBox().divider,
                  24.toHeight,
                  ValueListenableBuilder(
                    valueListenable: sbm.couponDiscount,
                    builder: (context, value, child) => Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (!payAgain) ...[
                          InfoTile(
                              title: LocalKeys.subtotal,
                              value: sbm.subTotalAmount(context).cur),
                          12.toHeight,
                          InfoTile(
                              title: LocalKeys.discount,
                              value: "- ${sbm.getCouponAmount(context).cur}"),
                          12.toHeight
                        ],
                        InfoTile(
                            title: LocalKeys.total,
                            value: (payAgain
                                    ? sbm.payAgainAmount
                                    : (sbm.subTotalAmount(context) -
                                        sbm.getCouponAmount(context)))
                                .cur)
                      ],
                    ),
                  ),
                  if (!payAgain) ...[
                    24.toHeight,
                    const SizedBox().divider,
                    24.toHeight,
                    Row(
                      children: [
                        Expanded(
                          flex: 2,
                          child: TextFormField(
                            controller: sbm.couponController,
                            decoration: InputDecoration(
                              hintText: LocalKeys.enterCode,
                            ),
                            onFieldSubmitted: (value) {
                              sbm.tryGettingCouponInfo(context);
                            },
                          ),
                        ),
                        12.toWidth,
                        Expanded(
                          flex: 1,
                          child: ValueListenableBuilder(
                            valueListenable: sbm.couponLoading,
                            builder: (context, value, child) => CustomButton(
                              onPressed: () {
                                sbm.tryGettingCouponInfo(context);
                              },
                              btText: LocalKeys.applyCoupon,
                              isLoading: value,
                            ),
                          ),
                        )
                      ],
                    )
                  ],
                  24.toHeight,
                  const SizedBox().divider,
                  24.toHeight,
                  const AcceptedAgreement(),
                  12.toHeight,
                  ValueListenableBuilder(
                      valueListenable: sbm.isLoading,
                      builder: (context, value, child) {
                        return CustomButton(
                          onPressed: () {
                            final sbm = ServiceBookingViewModel.instance;
                            if (payAgain) {
                              sbm.tryPayAgain(context);
                              return;
                            }
                            sbm.tryPlacingCartOrder(context);
                          },
                          btText: payAgain
                              ? LocalKeys.payNow
                              : LocalKeys.payAndConfirmOrder,
                          isLoading: value,
                        );
                      }),
                  24.toHeight,
                ],
              ),
            )),
          );
        }),
      ),
    );
  }
}
