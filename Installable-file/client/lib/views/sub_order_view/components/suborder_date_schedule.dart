import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';

import '../../../helper/local_keys.g.dart';

class SuborderDateSchedule extends StatelessWidget {
  final SubOrder subOrder;
  const SuborderDateSchedule({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return Container(
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        children: [
          InfoTile(
            title: LocalKeys.id,
            value: "#${subOrder.id}",
            valueColor: primaryColor,
          ),
          Divider(color: context.color.primaryBorderColor, height: 32),
          InfoTile(
            title: LocalKeys.status,
            value: subOrder.status.toString().getSuborderStatus,
            valueColor:
                subOrder.status.toString().getSuborderPrimaryStatusColor,
          ),
          Divider(color: context.color.primaryBorderColor, height: 32),
          InfoTile(
            title: LocalKeys.date,
            value:
                subOrder.date == null
                    ? LocalKeys.na
                    : DateFormat(
                      "EEE, dd MMMM",
                      context.dProvider.languageSlug,
                    ).format(subOrder.date!),
          ),
          Divider(color: context.color.primaryBorderColor, height: 32),
          InfoTile(
            title: LocalKeys.schedule,
            value: subOrder.schedule ?? LocalKeys.na,
          ),
        ],
      ),
    );
  }
}
