import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_label.dart';

import '../../../view_models/order_list_view_model/order_status_enums.dart';
import 'order_sheet_filter_buttons.dart';

class OrderListFilterSheet extends StatelessWidget {
  final ValueNotifier<PaymentStatus?> paymentStatus;

  final ValueNotifier<BookingStatus?> bookingStatus;

  const OrderListFilterSheet(
      {super.key, required this.paymentStatus, required this.bookingStatus});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
        padding: 24.paddingAll,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Align(
              alignment: Alignment.center,
              child: Container(
                height: 4,
                width: 48,
                margin: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12),
                  color: context.color.mutedContrastColor,
                ),
              ),
            ),
            FieldLabel(label: LocalKeys.orderStatus),
            ValueListenableBuilder(
              valueListenable: bookingStatus,
              builder: (context, value, child) {
                return Wrap(
                  spacing: 6,
                  runSpacing: 6,
                  children: bookingStatusValues.map.keys.map((status) {
                    return _button(
                        title: status.capitalize,
                        isSelected: bookingStatusValues.map[status] == value,
                        onPressed: () {
                          bookingStatus.value = bookingStatusValues.map[status];
                          debugPrint(bookingStatus.value.toString());
                        });
                  }).toList(),
                );
              },
            ),
            16.toHeight,
            OrderSheetFilterButtons(
                bookingStatus: bookingStatus, paymentStatus: paymentStatus),
          ],
        ));
  }

  Widget _button(
      {required String title,
      bool isSelected = false,
      required void Function()? onPressed}) {
    return isSelected
        ? ElevatedButton.icon(
            onPressed: () {},
            label: Text(title),
          )
        : OutlinedButton.icon(
            onPressed: onPressed,
            label: Text(title),
          );
  }
}
