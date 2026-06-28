import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/booking_services/hire_provider_from_offer_service.dart';
import 'package:provider/provider.dart';

import '../../../view_models/hiring_payment_choose_view_model/hiring_payment_choose_view_model.dart';
import '../../hiring_payment_choose_view/hiring_payment_choose_view.dart';

class JobSummeryButton extends StatelessWidget {
  const JobSummeryButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ElevatedButton(
          onPressed: () {
            HiringPaymentChooseViewModel.dispose;
            Provider.of<HireProviderFromOfferService>(context, listen: false)
                .reset();
            context.toPage(const HiringPaymentChooseView());
          },
          child: Text(LocalKeys.hireNow)),
    );
  }
}
