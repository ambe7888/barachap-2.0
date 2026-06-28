import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/order_services/order_details_service.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/order_details_view/components/order_details_staffs.dart';
import 'package:provider/provider.dart';

import '../../utils/components/custom_button.dart';
import '../order_details_view/components/order_details_earning.dart';
import '../order_details_view/components/order_details_note.dart';
import '../order_details_view/components/order_details_service_tile.dart';

class AcceptOrderView extends StatelessWidget {
  const AcceptOrderView({super.key});

  @override
  Widget build(BuildContext context) {
    final od = Provider.of<OrderDetailsService>(context, listen: false);
    final orderDetails = od.orderDetailsModel.orderDetails!;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          LocalKeys.acceptOrder,
        ),
      ),
      body: SingleChildScrollView(
        padding: 8.paddingV,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            OrderDetailsServiceTile(orderDetails: orderDetails),
            8.toHeight,
            if (orderDetails.staff?.fullname != null) ...[
              OrderDetailsStaffs(
                staff: orderDetails.staff,
              )
            ],
            8.toHeight,
            OrderDetailsEarning(orderDetails: orderDetails),
            8.toHeight,
            if (orderDetails.orderNote?.isNotEmpty ?? false) ...[
              8.toHeight,
              OrderDetailsNote(note: orderDetails.orderNote, fromAccept: true)
            ],
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: CustomButton(
            onPressed: () {
              Alerts().confirmationAlert(
                  context: context,
                  title: LocalKeys.areYouSure,
                  buttonText: LocalKeys.accept,
                  buttonColor: primaryColor,
                  onConfirm: () async {
                    final result = await od.tryAcceptOrder(
                        orderId: orderDetails.orderId, id: orderDetails.id);
                    if (result != true) return;
                    context.pop;
                    context.pop;
                  });
            },
            btText: LocalKeys.accept),
      ),
    );
  }
}
