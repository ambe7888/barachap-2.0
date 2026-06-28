import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../models/address_models/address_model.dart';
import '../../service_booking_views/components/address_select_sheet.dart';

class BookingSummaryAddress extends StatelessWidget {
  final ValueNotifier<Address?> address;
  const BookingSummaryAddress({super.key, required this.address});

  @override
  Widget build(BuildContext context) {
    return ValueListenableBuilder(
        valueListenable: address,
        builder: (context, value, child) {
          return Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Row(
              children: [
                Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        LocalKeys.serviceAddress,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: context.bodySmall?.copyWith(
                            color: context.color.tertiaryContrastColo),
                      ),
                      6.toHeight,
                      Text(
                        address.value?.address ?? "---",
                        style: context.titleSmall?.bold,
                      ),
                    ],
                  ),
                ),
                12.toWidth,
                GestureDetector(
                  onTap: () {
                    showModalBottomSheet(
                      context: context,
                      isScrollControlled: true,
                      backgroundColor: context.color.accentContrastColor,
                      builder: (context) {
                        return AddressSelectSheet(selectedAddress: address);
                      },
                    );
                  },
                  child: Container(
                    padding: 10.paddingAll,
                    decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(color: primaryColor)),
                    child: SvgAssets.pencil.toSVGSized(24, color: primaryColor),
                  ),
                ),
              ],
            ),
          );
        });
  }
}
