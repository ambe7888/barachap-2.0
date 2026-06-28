import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:url_launcher/url_launcher.dart' as urlLauncher;

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';

class OrderDetailsPhone extends StatelessWidget {
  final String? phone;
  const OrderDetailsPhone({
    super.key,
    required this.phone,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Row(
        children: [
          Expanded(
            flex: 1,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.phone,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                6.toHeight,
                Text(
                  phone ?? LocalKeys.na,
                  style: context.titleSmall?.bold,
                ),
              ],
            ),
          ),
          8.toWidth,
          if (phone != null)
            GestureDetector(
              onTap: () async {
                final Uri telUrl = Uri(scheme: 'tel', path: phone.toString());

                if (await urlLauncher.canLaunchUrl(telUrl)) {
                  await urlLauncher.launchUrl(telUrl);
                } else {
                  throw 'Could not open the map.';
                }
              },
              child: Container(
                padding: 10.paddingAll,
                decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: primaryColor)),
                child: SvgAssets.phone.toSVGSized(24, color: primaryColor),
              ),
            ),
        ],
      ),
    );
  }
}
