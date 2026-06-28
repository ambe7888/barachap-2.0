import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/offer_details_service.dart';
import 'package:prohandy_client/services/payment/payment_gateway_service.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/hiring_payment_choose_view_model/hiring_payment_choose_view_model.dart';
import 'package:prohandy_client/views/payment_views/payment_gateways.dart';
import 'package:provider/provider.dart';

import '../../view_models/application_details_view_model/application_details_view_model.dart';
import '../sign_up_view/components/accepted_agreement.dart';

class HiringPaymentChooseView extends StatelessWidget {
  const HiringPaymentChooseView({super.key});

  @override
  Widget build(BuildContext context) {
    final hpm = HiringPaymentChooseViewModel.instance;
    final adm = OfferDetailsViewModel.instance;
    final odProvider = Provider.of<OfferDetailsService>(context, listen: false);
    return ChangeNotifierProvider(
      create: (context) => PaymentGatewayService(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.payment),
        ),
        body: Scrollbar(
            child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              0.toHeight.divider,
              24.toHeight,
              InfoTile(
                      title: LocalKeys.total,
                      value: ((odProvider.offerDetailsService.message?.budget ??
                                  0) +
                              adm.getCalculatedTax((odProvider
                                      .offerDetailsService.message?.budget ??
                                  0)))
                          .cur)
                  .hp20,
              24.toHeight,
              0.toHeight.divider,
              24.toHeight,
              Text(
                LocalKeys.choosePaymentMethod,
                style: context.headlineLarge?.bold,
              ).hp20,
              24.toHeight,
              PaymentGateways(
                gatewayNotifier: hpm.selectedGateway,
                attachmentNotifier: hpm.manualPaymentImage,
                cardController: hpm.aCardController,
                secretCodeController: hpm.authCodeController,
                zUsernameController: hpm.zUsernameController,
                expireDateNotifier: hpm.authNetExpireDate,
                usernameController: TextEditingController(),
              ).hp20,
              24.toHeight,
              const SizedBox().divider,
              24.toHeight,
              const AcceptedAgreement().hp20,
              12.toHeight,
              ValueListenableBuilder(
                valueListenable: hpm.isLoading,
                builder: (context, value, child) => CustomButton(
                  onPressed: () {
                    final hpm = HiringPaymentChooseViewModel.instance;
                    hpm.tryHiringProvider(context);
                  },
                  btText: LocalKeys.payAndConfirmOrder,
                  isLoading: value,
                ).hp20,
              ),
              24.toHeight,
            ],
          ),
        )),
      ),
    );
  }
}
