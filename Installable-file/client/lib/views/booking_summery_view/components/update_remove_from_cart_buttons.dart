import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/alerts.dart';

import '../../../helper/local_keys.g.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';

class UpdateRemoveFromCartButtons extends StatelessWidget {
  const UpdateRemoveFromCartButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final svm = ServiceBookingViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: OutlinedButton(
                onPressed: () {
                  Alerts().confirmationAlert(
                      context: context,
                      title: LocalKeys.areYouSure,
                      buttonText: LocalKeys.remove,
                      onConfirm: () async {
                        svm.tryRemoveCart(context);
                        context.pop;
                      });
                },
                child: Text(LocalKeys.removeFromCart)),
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: ElevatedButton(
                onPressed: () {
                  Alerts().confirmationAlert(
                      context: context,
                      title: LocalKeys.areYouSure,
                      buttonText: LocalKeys.updateItem,
                      buttonColor: primaryColor,
                      onConfirm: () async {
                        svm.tryUpdateCart(context);
                        context.pop;
                      });
                },
                child: Text(LocalKeys.updateItem)),
          ),
        ],
      ),
    );
  }
}
