import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/attachment_select.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '/helper/svg_assets.dart';
import '../../models/payment_gateway_model.dart';
import '../../services/payment/payment_gateway_service.dart';
import 'components/auth_net_card_infos.dart';

class PaymentGateways extends StatelessWidget {
  final ValueNotifier<Gateway?> gatewayNotifier;
  final ValueNotifier<File?> attachmentNotifier;
  final TextEditingController cardController;
  final TextEditingController usernameController;
  final TextEditingController secretCodeController;
  final TextEditingController zUsernameController;
  final ValueNotifier<DateTime?> expireDateNotifier;
  const PaymentGateways({
    super.key,
    required this.gatewayNotifier,
    required this.attachmentNotifier,
    required this.cardController,
    required this.usernameController,
    required this.secretCodeController,
    required this.zUsernameController,
    required this.expireDateNotifier,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer<PaymentGatewayService>(builder: (context, pg, child) {
      return FutureBuilder(
          future: pg.shouldAutoFetch ? pg.fetchGateways() : null,
          builder: (context, snap) {
            if (snap.connectionState == ConnectionState.waiting) {
              return const CustomPreloader();
            }
            return Container(
              decoration: BoxDecoration(
                color: context.color.accentContrastColor,
              ),
              child: ValueListenableBuilder(
                valueListenable: gatewayNotifier,
                builder: (context, value, child) => Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Row(),
                      Wrap(
                        spacing: 8,
                        runSpacing: 12,
                        alignment: WrapAlignment.center,
                        children: pg.gatewayList
                            .map((e) => GestureDetector(
                                  onTap: () {
                                    gatewayNotifier.value = e;
                                    debugPrint(e.toJson().toString());
                                  },
                                  child: e.name == "cash_on_delivery"
                                      ? SquircleContainer(
                                          width: context.width - 48,
                                          radius: 12,
                                          borderColor: value?.name == e.name
                                              ? primaryColor
                                              : context.color.primaryBorderColor,
                                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                                          child: Row(
                                            mainAxisAlignment: MainAxisAlignment.center,
                                            children: [
                                              Icon(
                                                Icons.handshake_outlined,
                                                color: value?.name == e.name
                                                    ? primaryColor
                                                    : context.color.primaryContrastColor,
                                              ),
                                              12.toWidth,
                                              Text(
                                                "Payer à la fin du service",
                                                style: context.bodyMedium?.copyWith(
                                                  fontWeight: FontWeight.w600,
                                                  color: value?.name == e.name
                                                      ? primaryColor
                                                      : context.color.primaryContrastColor,
                                                ),
                                              ),
                                            ],
                                          ),
                                        )
                                      : Column(
                                          mainAxisSize: MainAxisSize.min,
                                          children: [
                                            SquircleContainer(
                                              radius: 10,
                                              borderColor: value?.name == e.name
                                                  ? primaryColor
                                                  : context.color.primaryBorderColor,
                                              padding: const EdgeInsets.all(6),
                                              child: ClipRRect(
                                                child: e.image.toString().endsWith(".svg")
                                                    ? SvgPicture.network(
                                                        e.image.toString())
                                                    : CustomNetworkImage(
                                                        height: 42,
                                                        width: (context.width / 4.3) -
                                                            12,
                                                        radius: 10,
                                                        imageUrl: e.image.toString(),
                                                        errorWidget: Column(
                                                          mainAxisAlignment:
                                                              MainAxisAlignment
                                                                  .center,
                                                          children: [
                                                            SvgAssets.gallery
                                                                .toSVGSized(18),
                                                          ],
                                                        ),
                                                      ),
                                              ),
                                            ),
                                            4.toHeight,
                                            SizedBox(
                                              width: (context.width / 4.3) - 12,
                                              child: Text(
                                                e.name == "manual_payment"
                                                    ? "Manuel"
                                                    : e.name.toString().replaceAll("_", " ").capitalizeWords,
                                                style: context.bodySmall?.copyWith(
                                                  fontSize: 9,
                                                  fontWeight: value?.name == e.name
                                                      ? FontWeight.bold
                                                      : FontWeight.normal,
                                                ),
                                                textAlign: TextAlign.center,
                                                maxLines: 2,
                                                overflow: TextOverflow.ellipsis,
                                              ),
                                            ),
                                          ],
                                        ),
                                ))
                            .toList(),
                      ),
                      if (gatewayNotifier.value?.name == "manual_payment") ...[
                        if (gatewayNotifier
                                .value?.credentials?.description?.isNotEmpty ??
                            false) ...[
                          12.toHeight,
                          SquircleContainer(
                            width: double.infinity,
                            padding: 12.paddingAll,
                            color: context.color.mutedContrastColor,
                            radius: 16,
                            child: HtmlWidget(
                              gatewayNotifier.value!.credentials!.description!,
                            ),
                          )
                        ],
                        12.toHeight,
                        AttachmentSelect(
                            attachmentNotifier: attachmentNotifier),
                      ],
                      if (gatewayNotifier.value?.name ==
                          "authorize_dot_net") ...[
                        12.toHeight,
                        AuthCardInfos(
                            cardController: cardController,
                            usernameController: usernameController,
                            secretCodeController: secretCodeController,
                            expireDateNotifier: expireDateNotifier),
                      ],
                      if (gatewayNotifier.value?.name == "zitopay") ...[
                        12.toHeight,
                        FieldWithLabel(
                          label: LocalKeys.username,
                          hintText: LocalKeys.enterUsername,
                          controller: zUsernameController,
                        ),
                      ],
                    ]),
              ),
            );
          });
    });
  }
}
