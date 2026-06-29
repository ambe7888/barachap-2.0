import 'dart:io';

import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/services/jobs/offer_details_service.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/payment_methode_navigator_helper.dart';
import 'package:prohandy_client/views/order_details_view/order_details_view.dart';
import 'package:provider/provider.dart';

import '../../helper/svg_assets.dart';
import '../../models/payment_gateway_model.dart';
import '../../services/booking_services/hire_provider_from_offer_service.dart';
import '../../utils/components/alerts.dart';
import '../landding_view_model/landding_view_model.dart';

class HiringPaymentChooseViewModel {
  final ValueNotifier<DateTime?> selectedDate = ValueNotifier(null);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<bool> paymentLoading = ValueNotifier(false);
  final ValueNotifier<File?> manualPaymentImage = ValueNotifier(null);

  bool fromCart = false;

  bool orderFromCart = true;

  TextEditingController aCardController = TextEditingController();
  TextEditingController zUsernameController = TextEditingController();
  TextEditingController authCodeController = TextEditingController();
  ValueNotifier<Gateway?> selectedGateway = ValueNotifier(null);
  ValueNotifier<DateTime?> authNetExpireDate = ValueNotifier(null);

  HiringPaymentChooseViewModel._init();
  static HiringPaymentChooseViewModel? _instance;
  static HiringPaymentChooseViewModel get instance {
    _instance ??= HiringPaymentChooseViewModel._init();
    return _instance!;
  }

  HiringPaymentChooseViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryHiringProvider(BuildContext context) async {
    if (isLoading.value) return;
    final po = Provider.of<HireProviderFromOfferService>(
      context,
      listen: false,
    );
    final jd = Provider.of<JobDetailsService>(context, listen: false);
    final od = Provider.of<OfferDetailsService>(context, listen: false);

    if (selectedGateway.value?.name == "manual_payment" &&
        manualPaymentImage.value == null) {
      LocalKeys.selectPaymentInfo.showToast();
      debugPrint(getToken.toString());
      return;
    }
    var result;
    isLoading.value = true;
    result = await po.tryPlacingOrder(
      od.offerDetailsService.message?.id,
      jd.jobDetailsModel.jobDetails?.id,
      selectedGateway.value?.name ?? "",
      manualPaymentImage.value,
    );

    if (result == true &&
        [
          "manual_payment",
          "cash_on_delivery",
          "cod",
        ].contains(selectedGateway.value?.name)) {
      await Alerts().showInfoDialogue(
        context: context,
        title: LocalKeys.paymentSuccess,
        description: LocalKeys.providerHiredSuccessfully,
        infoAsset: SvgAssets.addFilled.toSVGSized(
          60,
          color: context.color.primarySuccessColor,
        ),
      );
      LandingViewModel.instance.navigateToLanding(context);
      context.toPage(OrderDetailsView(orderId: po.orderId));
    } else if (result == true) {
      final po = Provider.of<HireProviderFromOfferService>(
        context,
        listen: false,
      );
      startPayment(
        context,
        selectedGateway: selectedGateway.value!,
        authNetCard: aCardController.text,
        authcCode: aCardController.text,
        zUsername: zUsernameController.text,
        authNetED: authNetExpireDate.value,
        orderId: po.orderId,
        amount: po.totalAmount,
        onSuccess: () async {
          Alerts().showLoading(context: context);
          try {
            paymentLoading.value = true;
            final result = await Provider.of<HireProviderFromOfferService>(
              context,
              listen: false,
            ).updatePayment(context);
            context.pop;
            if (result != true) {
              context.snackBar(
                LocalKeys.paymentUpdateFailed,
                buttonText: LocalKeys.retry,
                onTap: () async {
                  paymentLoading.value = true;

                  await Provider.of<HireProviderFromOfferService>(
                    context,
                    listen: false,
                  ).updatePayment(context);

                  paymentLoading.value = false;
                },
              );
            } else {
              await Alerts().showInfoDialogue(
                context: context,
                title: LocalKeys.paymentSuccess,
                description: LocalKeys.providerHiredSuccessfully,
                infoAsset: SvgAssets.addFilled.toSVGSized(
                  60,
                  color: context.color.primarySuccessColor,
                ),
              );
              LandingViewModel.instance.navigateToLanding(context);
              context.toPage(OrderDetailsView(orderId: po.orderId));
            }
          } catch (e) {
            debugPrint(e.toString());
          } finally {
            paymentLoading.value = false;
          }
        },
        onFailed: () async {
          await Alerts().showInfoDialogue(
            context: context,
            title: LocalKeys.paymentFailed,
            description: LocalKeys.providerHiredButPaymentProcessFailed,
            infoAsset: SvgAssets.addFilled.toSVGSized(
              60,
              color: context.color.primarySuccessColor,
            ),
          );
          LandingViewModel.instance.navigateToLanding(context);
          context.toPage(OrderDetailsView(orderId: po.orderId));
        },
      );
    }
    isLoading.value = false;
  }
}
