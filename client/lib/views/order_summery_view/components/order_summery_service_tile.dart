import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';

import '../../../helper/constant_helper.dart';
import '../../../models/order_models/order_response_model.dart';
import '../../../utils/components/custom_network_image.dart';

class OrderSummeryServiceTile extends StatelessWidget {
  final SubOrder subOrder;
  const OrderSummeryServiceTile({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
      child: Row(
        children: [
          CustomNetworkImage(
            height: 72,
            width: 72,
            radius: 10,
            fit: BoxFit.cover,
            imageUrl: subOrder.service?.image,
          ),
          12.toWidth,
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SquircleContainer(
                    radius: 4,
                    color: context.color.mutedPendingColor,
                    padding:
                        const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    child: Text(
                      LocalKeys.pending,
                      style: context.bodySmall?.copyWith(
                        color: context.color.primaryPendingColor,
                      ),
                    )),
                6.toHeight,
                Text(
                  "${DateFormat("EEE, dd MMM", dProvider.languageSlug).format(subOrder.date!)} . ${subOrder.schedule ?? "N/A"}",
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.titleMedium?.bold,
                ),
                6.toHeight,
                RichText(
                    text: TextSpan(
                        text: LocalKeys.billed,
                        style: context.titleSmall?.bold,
                        children: [
                      TextSpan(
                        text: " ${subOrder.total.cur}",
                        style: context.titleSmall?.bold
                            .copyWith(color: primaryColor),
                      )
                    ]))
              ],
            ),
          )
        ],
      ),
    );
  }
}
