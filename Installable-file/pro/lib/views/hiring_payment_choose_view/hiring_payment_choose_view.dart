import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/payment_services/payment_gateway_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/info_tile.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/payment_views/payment_gateways.dart';
import 'package:provider/provider.dart';

import '../sign_up_view/components/accepted_aggreement.dart';

class HiringPaymentChooseView extends StatelessWidget {
  const HiringPaymentChooseView({super.key});

  @override
  Widget build(BuildContext context) {
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
              InfoTile(title: LocalKeys.total, value: 2354.cur).hp20,
              24.toHeight,
              0.toHeight.divider,
              24.toHeight,
              Text(
                LocalKeys.choosePaymentMethod,
                style: context.headlineLarge?.bold,
              ).hp20,
              24.toHeight,
              PaymentGateways(
                      gatewayNotifier: ValueNotifier(null),
                      attachmentNotifier: ValueNotifier(null),
                      cardController: TextEditingController(),
                      usernameController: TextEditingController(),
                      secretCodeController: TextEditingController(),
                      zUsernameController: TextEditingController(),
                      expireDateNotifier: ValueNotifier(null))
                  .hp20,
              24.toHeight,
              const SizedBox().divider,
              24.toHeight,
              const AcceptedAgreement().hp20,
              12.toHeight,
              CustomButton(
                onPressed: () {},
                btText: LocalKeys.payAndConfirmOrder,
              ).hp20,
              24.toHeight,
            ],
          ),
        )),
      ),
    );
  }
}
