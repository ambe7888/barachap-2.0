import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/view_models/request_withdraw_view_model/request_withdraw_view_model.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../request_withdraw_view/request_withdraw_view.dart';

class WithdrawRequestButton extends StatelessWidget {
  const WithdrawRequestButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: CustomButton(
          onPressed: () {
            RequestWithdrawViewModel.dispose;
            context.toPage(const RequestWithdrawView());
          },
          btText: LocalKeys.requestWithdraw),
    );
  }
}
