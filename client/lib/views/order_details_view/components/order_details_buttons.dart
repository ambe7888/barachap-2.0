import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/services/order_services/order_details_service.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart' as urlLauncher;

import '../../../helper/local_keys.g.dart';

class OrderDetailsButtons extends StatelessWidget {
  const OrderDetailsButtons({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        border: Border(
          top: BorderSide(color: context.color.primaryBorderColor),
        ),
      ),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: ElevatedButton.icon(
              onPressed: () async {
                final url =
                    "${AppUrls.invoiceUrl}/${Provider.of<OrderDetailsService>(context, listen: false).orderDetailsModel.orderDetails?.id}";
                debugPrint(url.toString());
                final Uri launchUri = Uri(
                  scheme: 'https',
                  path: url.replaceAll("https://", ""),
                );
                await urlLauncher.launchUrl(
                  launchUri,
                  mode: urlLauncher.LaunchMode.externalApplication,
                );
              },
              label: Text(LocalKeys.downloadInvoice),
              icon: SvgAssets.download.toSVGSized(
                24,
                color: context.color.accentContrastColor,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
