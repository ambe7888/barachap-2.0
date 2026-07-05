import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/services/booking_services/booking_addons_service.dart';
import 'package:prohandy_client/services/booking_services/place_order_service.dart';
import 'package:prohandy_client/services/profile_services/profile_info_service.dart';
import 'package:prohandy_client/services/service/cart_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/view_models/order_list_view_model/order_list_view_model.dart';
import 'package:prohandy_client/view_models/service_booking_view_model/payment_methode_navigator_helper.dart';
import 'package:prohandy_client/views/order_details_view/order_details_view.dart';
import 'package:provider/provider.dart';

import '../../models/payment_gateway_model.dart';
import '../../models/schedule_list_model.dart';
import '../../models/service/service_details_model.dart';
import '../../services/booking_services/coupon_info_service.dart';
import '../../views/order_summery_view/order_summery_view.dart';
import '../landding_view_model/landding_view_model.dart';

class ServiceBookingViewModel {
  final ValueNotifier<DateTime?> selectedDate = ValueNotifier(null);
  final ValueNotifier<Schedule?> selectedSchedule = ValueNotifier(null);
  final ValueNotifier<Address?> selectedAddress = ValueNotifier(null);
  final ValueNotifier<Staff?> selectedStaff = ValueNotifier(null);
  final ValueNotifier<ServiceDetails?> selectedService = ValueNotifier(null);
  final ValueNotifier<Map> addons = ValueNotifier({});
  final ValueNotifier<bool> isLoading = ValueNotifier(false);
  final ValueNotifier<bool> paymentLoading = ValueNotifier(false);
  final ValueNotifier<bool> couponLoading = ValueNotifier(false);
  final ValueNotifier<File?> manualPaymentImage = ValueNotifier(null);

  dynamic _orderDetailsId;
  num _payAgainAmount = 0;
  num get payAgainAmount => _payAgainAmount;
  final ValueNotifier<num> taxNotifier = ValueNotifier(0);
  final ValueNotifier<CouponInfoModel?> couponDiscount = ValueNotifier(null);
  num get tax => taxNotifier.value;
  String _taxType = "percentage";
  String get taxType => _taxType;
  bool taxCalculated = false;

  setTax(num amount, type) {
    taxNotifier.value = amount;
    _taxType = type ?? "percentage";
    taxCalculated = true;
  }

  num get getCalculatedTax {
    return 0;
  }

  num getCouponAmount(BuildContext context) {
    if ((couponDiscount.value?.coupon?.discount ?? 0) <= 0) return 0;
    if (couponDiscount.value?.coupon?.discountType != "percentage") {
      return couponDiscount.value?.coupon?.discount ?? 0;
    }
    num tempAmount = 0;
    num subTotal = 0;
    if (!orderFromCart) {
      num addonTotal = 0;
      final addonItems =
          addons.value.values
              .map((addon) => addon["price"] * addon["quantity"])
              .toList();
      for (var t in addonItems) {
        addonTotal += t;
      }
      subTotal =
          ((selectedService.value?.discountPrice ?? 0) > 0
              ? selectedService.value!.discountPrice!
              : (selectedService.value?.price ?? 0)) +
          addonTotal;
      tempAmount = (couponDiscount.value!.coupon!.discount / 100) * subTotal;
    } else {
      final cs = Provider.of<CartService>(context, listen: false);
      subTotal = cs.subTotal + cs.taxTotal;
      tempAmount = (couponDiscount.value!.coupon!.discount / 100) * subTotal;
    }
    return tempAmount;
  }

  get orderDetailsId => _orderDetailsId;

  bool fromCart = false;

  bool orderFromCart = true;

  final TextEditingController descriptionController = TextEditingController();
  final TextEditingController couponController = TextEditingController();

  ValueNotifier dateScheduleType = ValueNotifier(SelectingScheduleType.date);

  TextEditingController aCardController = TextEditingController();
  TextEditingController zUsernameController = TextEditingController();
  TextEditingController authCodeController = TextEditingController();
  ValueNotifier<Gateway?> selectedGateway = ValueNotifier(null);
  ValueNotifier<DateTime?> authNetExpireDate = ValueNotifier(null);

  num subTotalAmount(BuildContext context) {
    num subTotal = 0;
    try {
      if (!orderFromCart) {
        num addonTotal = 0;
        final addonItems =
            addons.value.values
                .map((addon) => addon["price"] * addon["quantity"])
                .toList();
        for (var t in addonItems) {
          addonTotal += t;
        }
        subTotal =
            ((selectedService.value?.discountPrice ?? 0) > 0
                ? selectedService.value!.discountPrice!
                : (selectedService.value?.price ?? 0)) +
            addonTotal +
            getCalculatedTax;
      } else {
        final cs = Provider.of<CartService>(context, listen: false);
        subTotal = cs.subTotal + cs.taxTotal;
      }
    } catch (e) {}
    return subTotal;
  }

  num get carItemTotalAmount {
    num subTotal = 0;
    try {
      subTotal +=
          (selectedService.value?.discountPrice ?? 0) > 0
              ? selectedService.value!.discountPrice!
              : (selectedService.value?.price ?? 0);
      addons.value.values.toList().forEach((ad) {
        subTotal +=
            ad["price"].toString().tryToParse *
            ad["quantity"].toString().tryToParse;
      });
    } catch (e) {}
    return subTotal;
  }

  bool get serviceBookingAddonViewValidate {
    if (selectedSchedule.value == null) {
      LocalKeys.selectASchedule.showToast();
      return false;
    } else if (selectedAddress.value == null) {
      LocalKeys.selectAddress.showToast();
      return false;
    }
    return true;
  }

  ServiceBookingViewModel._init();
  static ServiceBookingViewModel? _instance;
  static ServiceBookingViewModel get instance {
    _instance ??= ServiceBookingViewModel._init();
    return _instance!;
  }

  ServiceBookingViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void setAddons(BuildContext context) {
    addons.value =
        Provider.of<BookingAddonsService>(context, listen: false).addons;
  }

  tryAddingCart(BuildContext context) async {
    final cProvider = Provider.of<CartService>(context, listen: false);

    cProvider.addToCart(
      selectedService.value?.id?.toString() ?? "",
      selectedService.value?.toMinimizedJson(),
      selectedAddress.value?.toJson(),
      selectedDate.value?.toIso8601String(),
      selectedSchedule.value?.toJson(),
      selectedStaff.value?.toMinimizedJson(),
      addons.value,
      descriptionController.text,
      getCalculatedTax.toString(),
      carItemTotalAmount,
    );
  }

  tryUpdateCart(BuildContext context) async {
    final cProvider = Provider.of<CartService>(context, listen: false);

    cProvider.updateToCart(
      _orderDetailsId?.toString() ?? selectedService.value?.id?.toString() ?? "",
      selectedService.value?.toMinimizedJson(),
      selectedAddress.value?.toJson(),
      selectedDate.value?.toIso8601String(),
      selectedSchedule.value?.toJson(),
      selectedStaff.value?.toMinimizedJson(),
      addons.value,
      descriptionController.text,
      getCalculatedTax.toString(),
      carItemTotalAmount,
    );
  }

  tryRemoveCart(BuildContext context) async {
    final cProvider = Provider.of<CartService>(context, listen: false);

    cProvider.deleteFromCart(_orderDetailsId?.toString() ?? selectedService.value?.id?.toString() ?? "");
    context.pop;
  }

  initCartItem(cartItem) {
    fromCart = true;
    _orderDetailsId = cartItem["serviceId"];
    selectedService.value = ServiceDetails.fromJson(cartItem["service"]);
    selectedAddress.value = Address.fromJson(cartItem["address"]);
    selectedDate.value = DateTime.tryParse(cartItem["date"].toString());
    selectedSchedule.value = Schedule.fromJson(cartItem["schedule"]);
    try {
      selectedStaff.value = Staff.fromJson(cartItem["staff"]);
    } catch (e) {}
    addons.value = cartItem["addons"];
    debugPrint(cartItem["addons"].toString());
    descriptionController.text = cartItem["note"];
  }

  setInstantBooking() {
    orderFromCart = false;
  }

  void tryPlacingCartOrder(BuildContext context) async {
    if (isLoading.value) return;
    final po = Provider.of<PlaceOrderService>(context, listen: false);
    final cs = Provider.of<CartService>(context, listen: false);

    if (selectedGateway.value?.name == "manual_payment" &&
        manualPaymentImage.value == null) {
      LocalKeys.selectPaymentInfo.showToast();
      debugPrint(getToken.toString());
      return;
    }
    var result;
    isLoading.value = true;
    if (orderFromCart) {
      final services = cs.servicesForOrder;
      final addons = cs.addonsForOrder;
      result = await po.tryPlacingOrder(
        services,
        addons,
        selectedGateway.value?.name ?? "",
        manualPaymentImage.value,
        couponController.text,
      );
    } else {
      final services = [
        {
          "service_id": selectedService.value?.id,
          "staff_id": selectedStaff.value?.id,
          "location_id": selectedAddress.value?.id,
          "date": DateFormat("yyyy-MM-dd").format(selectedDate.value!),
          "schedule": selectedSchedule.value?.value,
          "order_note": descriptionController.text,
        },
      ];
      final addOns = [];
      for (var a in addons.value.values.toList()) {
        try {
          addOns.add({
            "addon_service_id": a["addon_service_id"],
            "service_id": a["service_id"],
            "quantity": a["quantity"],
          });
        } catch (e) {}
      }
      result = await po.tryPlacingOrder(
        services,
        addOns,
        selectedGateway.value?.name ?? "",
        manualPaymentImage.value,
        couponController.text,
      );
    }
    if (result == true &&
        [
          "manual_payment",
          "cash_on_delivery",
          "cod",
        ].contains(selectedGateway.value?.name)) {
      OrderListViewModel.instance.orderListRK.currentState?.show();
      context.toPage(const OrderSummeryView());
    } else if (result == true) {
      OrderListViewModel.instance.orderListRK.currentState?.show();
      final po = Provider.of<PlaceOrderService>(context, listen: false);
      final piProvider = Provider.of<ProfileInfoService>(
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
        orderId: po.orderResponseModel.orderDetails?.id,
        amount: po.orderResponseModel.orderDetails?.total,
        userEmail: piProvider.profileInfoModel.userDetails?.email,
        userPhone: piProvider.profileInfoModel.userDetails?.phone,
        userName: piProvider.profileInfoModel.userDetails?.firstName,
        onSuccess: () {
          context.toPage(
            OrderSummeryView(
              updateFunction: (cxt) async {
                try {
                  paymentLoading.value = true;
                  final result = await Provider.of<PlaceOrderService>(
                    cxt,
                    listen: false,
                  ).updatePayment(cxt);
                  if (result != true) {
                    cxt.snackBar(
                      LocalKeys.paymentUpdateFailed,
                      buttonText: LocalKeys.retry,
                      onTap: () async {
                        paymentLoading.value = true;

                        await Provider.of<PlaceOrderService>(
                          cxt,
                          listen: false,
                        ).updatePayment(cxt);

                        paymentLoading.value = false;
                      },
                    );
                  }
                } catch (e) {
                  debugPrint(e.toString());
                } finally {
                  paymentLoading.value = false;
                }
              },
            ),
          );
        },
        onFailed: () {
          context.toPage(const OrderSummeryView());
        },
      );
    }

    if (orderFromCart && result == true) {
      cs.clearCart();
    }
    isLoading.value = false;
  }

  tryPayAgain(BuildContext context) async {
    if (selectedGateway.value?.name == null) {
      LocalKeys.choosePaymentMethod.showToast();
      return;
    }
    final piProvider = Provider.of<ProfileInfoService>(context, listen: false);
    startPayment(
      context,
      selectedGateway: selectedGateway.value!,
      authNetCard: aCardController.text,
      authcCode: aCardController.text,
      zUsername: zUsernameController.text,
      authNetED: authNetExpireDate.value,
      orderId: orderDetailsId,
      amount: payAgainAmount,
      userEmail: piProvider.profileInfoModel.userDetails?.email,
      userPhone: piProvider.profileInfoModel.userDetails?.phone,
      userName: piProvider.profileInfoModel.userDetails?.firstName,
      onSuccess: () async {
        Alerts().showLoading(context: context);
        try {
          paymentLoading.value = true;
          final result = await Provider.of<PlaceOrderService>(
            context,
            listen: false,
          ).updatePayment(context, id: orderDetailsId);
          context.pop;
          if (result != true) {
            debugPrint(orderDetailsId.toString());
            context.snackBar(
              LocalKeys.paymentUpdateFailed,
              buttonText: LocalKeys.retry,
              onTap: () async {
                paymentLoading.value = true;

                await Provider.of<PlaceOrderService>(
                  context,
                  listen: false,
                ).updatePayment(context, id: orderDetailsId);

                paymentLoading.value = false;
              },
            );
          } else {
            OrderListViewModel.instance.orderDetailsRK.currentState?.show();
            OrderListViewModel.instance.orderListRK.currentState?.show();
            await Alerts().showInfoDialogue(
              context: context,
              title: LocalKeys.paymentSuccess,
              description: LocalKeys.paymentProcessedSuccessfully,
              infoAsset: SvgAssets.addFilled.toSVGSized(
                60,
                color: context.color.primarySuccessColor,
              ),
            );
            LandingViewModel.instance.navigateToLanding(context);
            context.toPage(OrderDetailsView(orderId: orderDetailsId));
          }
        } catch (e) {
          debugPrint(e.toString());
        } finally {
          paymentLoading.value = false;
        }
      },
      onFailed: () {
        LocalKeys.paymentUpdateFailed.showToast();
        context.pop;
        context.pop;
      },
    );
  }

  void setPayableAmount(num amount, dynamic id) {
    _payAgainAmount = amount;
    _orderDetailsId = id;
  }

  void tryGettingCouponInfo(BuildContext context) async {
    couponLoading.value = true;
    await CouponInfoService().fetchCouponInfo();
    couponLoading.value = false;
  }
}

enum SelectingScheduleType { date, schedule }
