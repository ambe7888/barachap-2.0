import 'package:flutter/material.dart';
import 'package:prohandy_client/services/booking_services/place_order_service.dart';
import 'package:prohandy_client/views/order_summery_view/components/order_summery_service_tile.dart';

class OrderSummeryService extends StatelessWidget {
  final PlaceOrderService po;
  const OrderSummeryService({super.key, required this.po});

  @override
  Widget build(BuildContext context) {
    return Wrap(
      runSpacing: 8,
      children: po.orderResponseModel.orderDetails?.subOrders?.map((subOrder) {
            return OrderSummeryServiceTile(
              subOrder: subOrder,
            );
          }).toList() ??
          [],
    );
  }
}
