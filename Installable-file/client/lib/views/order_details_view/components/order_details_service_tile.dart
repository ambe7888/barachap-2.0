import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/views/sub_order_view/sub_order_view.dart';

import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_network_image.dart';

class OrderDetailsServiceTile extends StatelessWidget {
  final SubOrder subOrder;
  const OrderDetailsServiceTile({super.key, required this.subOrder});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(SubOrderView(subOrder: subOrder));
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        color: context.color.accentContrastColor,
        child: Row(
          children: [
            if (subOrder.service != null) ...[
              CustomNetworkImage(
                height: 72,
                width: 72,
                radius: 10,
                fit: BoxFit.cover,
                imageUrl: subOrder.service?.image,
              ),
              12.toWidth
            ],
            if (subOrder.jobPostId != null) ...[
              SquircleContainer(
                height: 72,
                width: 72,
                radius: 10,
                color: context.color.mutedContrastColor,
                child: Center(
                  child: SvgAssets.jobs.toSVGSized(32,
                      color: context.color.secondaryContrastColor),
                ),
              ),
              12.toWidth
            ],
            Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Wrap(
                    spacing: 6,
                    runSpacing: 6,
                    children: [
                      SquircleContainer(
                          radius: 4,
                          color: subOrder.status
                              .toString()
                              .getSuborderMutedStatusColor,
                          padding: const EdgeInsets.symmetric(
                              horizontal: 8, vertical: 4),
                          child: Text(
                            subOrder.status.toString().getSuborderStatus,
                            style: context.bodySmall?.copyWith(
                              color: subOrder.status
                                  .toString()
                                  .getSuborderPrimaryStatusColor,
                            ),
                          )),
                      if (subOrder.job != null)
                        SquircleContainer(
                            radius: 4,
                            borderColor: context.color.primaryBorderColor,
                            padding: const EdgeInsets.symmetric(
                                horizontal: 8, vertical: 4),
                            child: Text(
                              LocalKeys.job,
                              style: context.bodySmall,
                            )),
                    ],
                  ),
                  6.toHeight,
                  Text(
                    "${subOrder.date == null ? LocalKeys.na : DateFormat("EEE, dd MMM", dProvider.languageSlug).format(subOrder.date!)} . ${subOrder.schedule ?? LocalKeys.na}",
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
      ),
    );
  }
}
