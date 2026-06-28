import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/request_withdraw_view_model/request_withdraw_view_model.dart';

class SendWithdrawRequest extends StatelessWidget {
  const SendWithdrawRequest({super.key});

  @override
  Widget build(BuildContext context) {
    final rwm = RequestWithdrawViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ValueListenableBuilder(
        valueListenable: rwm.isLoading,
        builder: (context, value, child) {
          return CustomButton(
            onPressed: () {
              rwm.trySendingRequest(context);
            },
            btText: LocalKeys.sendRequest,
            isLoading: value,
          );
        },
      ),
    );
  }
}
