import 'package:flutter/material.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:prohandy_client/views/order_details_view/components/order_details_service_tile.dart';

class OrderDetailsSubOrders extends StatelessWidget {
  final OrderDetailsService od;
  const OrderDetailsSubOrders({super.key, required this.od});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      runSpacing: 8,
      children: od.orderDetailsModel.orderDetails?.subOrders!.map((subOrder) {
            return OrderDetailsServiceTile(subOrder: subOrder);
          }).toList() ??
          [],
    );
  }
}
