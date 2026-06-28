import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/view_models/order_list_view_model/order_status_enums.dart';

import '../../../helper/local_keys.g.dart';
import '../../../view_models/order_list_view_model/order_list_view_model.dart';

class OrderSheetFilterButtons extends StatelessWidget {
  final ValueNotifier<BookingStatus?> bookingStatus;
  final ValueNotifier<PaymentStatus?> paymentStatus;
  const OrderSheetFilterButtons(
      {super.key, required this.bookingStatus, required this.paymentStatus});

  @override
  Widget build(BuildContext context) {
    final olm = OrderListViewModel.instance;
    return Row(
      children: [
        Expanded(
          flex: 1,
          child: OutlinedButton(
            onPressed: () {
              olm.setFilterStatus(null, null, context);
              context.pop;
            },
            child: Text(LocalKeys.resetFilter),
          ),
        ),
        12.toWidth,
        Expanded(
          flex: 1,
          child: ElevatedButton(
            onPressed: () {
              debugPrint(bookingStatus.toString());
              olm.setFilterStatus(
                  bookingStatus.value, paymentStatus.value, context);
              context.pop;
            },
            child: Text(LocalKeys.applyFilter),
          ),
        ),
      ],
    );
  }
}
