import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/request_withdraw_service.dart';

class RequestWithdrawViewModel {
  ValueNotifier selectedGateway = ValueNotifier(null);
  final TextEditingController amountController = TextEditingController();
  List<TextEditingController> inputFieldControllers = [];

  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  final GlobalKey<FormState> formKey = GlobalKey();

  RequestWithdrawViewModel._init();
  static RequestWithdrawViewModel? _instance;
  static RequestWithdrawViewModel get instance {
    _instance ??= RequestWithdrawViewModel._init();
    return _instance!;
  }

  RequestWithdrawViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  setSelectedGateway(RequestWithdrawService rw, value) {
    final gateway = rw.withdrawSettingsModel.withdrawGateways
        .firstWhere((element) => element.name == value);
    selectedGateway.value = gateway;
    inputFieldControllers.clear();
    for (var g in gateway.field) {
      inputFieldControllers.add(TextEditingController());
    }
  }

  trySendingRequest(BuildContext context) async {
    if (formKey.currentState?.validate() != true) return;
    isLoading.value = true;
    await RequestWithdrawService().tryWithdrawRequest().then((v) {
      if (v != true) return;
      context.pop;
    });
    isLoading.value = false;
  }
}
