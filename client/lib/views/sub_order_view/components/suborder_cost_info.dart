import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';

import '../../../view_models/application_details_view_model/application_details_view_model.dart';
import 'suborder_addons.dart';

class SuborderCostInfo extends StatelessWidget {
  final SubOrder subOrder;
  const SuborderCostInfo({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    final adm = OfferDetailsViewModel.instance;
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          InfoTile(title: LocalKeys.package, value: subOrder.basicPrice.cur),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          SuborderAddons(addons: subOrder.subOrderAddons),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(title: LocalKeys.subtotal, value: subOrder.subTotal.cur),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(title: LocalKeys.total, value: (subOrder.total - (subOrder.tax ?? 0)).cur),
        ],
      ),
    );
  }
}
