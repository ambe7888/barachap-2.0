import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/models/address_models/address_model.dart';
import 'package:url_launcher/url_launcher.dart' as urlLauncher;

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';

class OrderDetailsAddress extends StatelessWidget {
  final Address? address;
  const OrderDetailsAddress({
    super.key,
    this.address,
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
                  LocalKeys.address,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.tertiaryContrastColo),
                ),
                6.toHeight,
                Text(
                  address?.address ?? LocalKeys.na,
                  style: context.titleSmall?.bold,
                ),
              ],
            ),
          ),
          8.toWidth,
          if (address?.latitude != null && address?.longitude != null)
            GestureDetector(
              onTap: () async {
                String googleUrl =
                    'https://www.google.com/maps/search/?api=1&query=${address?.latitude},${address?.longitude}';
                if (await urlLauncher.canLaunch(googleUrl)) {
                  await urlLauncher.launch(googleUrl);
                } else {
                  throw 'Could not open the map.';
                }
              },
              child: Container(
                padding: 10.paddingAll,
                decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: primaryColor)),
                child: SvgAssets.location.toSVGSized(24, color: primaryColor),
              ),
            ),
        ],
      ),
    );
  }
}
