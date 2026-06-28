import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:prohand/utils/components/currency_icon.dart';
import 'package:prohand/utils/components/marquee.dart';
import 'package:provider/provider.dart';

import '../../../models/service_models/service_list_model.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/service_card/components/service_card_sub_info.dart';
import '../../service_details_view/service_details_view.dart';

class MyServiceTile extends StatelessWidget {
  final AllService service;
  const MyServiceTile({super.key, required this.service});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        context.toPage(
            ServiceDetailsView(
              id: service.id,
            ), then: (_) {
          Provider.of<ServiceDetailsService>(context, listen: false).reset();
        });
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
        color: Colors.transparent,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CustomNetworkImage(
                  height: 48,
                  width: 48,
                  radius: 10,
                  fit: BoxFit.cover,
                  imageUrl: service.image,
                ),
                12.toWidth,
                Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        service.title ?? "---",
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: context.titleSmall?.bold,
                      ),
                      4.toHeight,
                      ServiceCardSubInfo(
                        avgRating: service.averageRating,
                        unit: service.unit ?? "",
                        category: service.category?.name,
                        soldCount: service.soldCount,
                      )
                    ],
                  ),
                )
              ],
            ),
            12.toHeight,
            Marquee(
                child: Wrap(
              children: [
                subInfo(
                    context,
                    const CurrencyIcon(),
                    LocalKeys.price,
                    (service.discountPrice > 0
                            ? service.discountPrice
                            : service.price)
                        .cur),
                subInfo(
                  context,
                  SvgAssets.invisible.toSVGSized(24,
                      color: context.color.tertiaryContrastColo),
                  LocalKeys.view,
                  "${service.view}",
                  isMiddle: true,
                ),
                subInfo(
                    context,
                    SvgAssets.list.toSVGSized(24,
                        color: context.color.tertiaryContrastColo),
                    LocalKeys.orders,
                    "${service.soldCount}"),
              ],
            ))
          ],
        ),
      ),
    );
  }

  Widget subInfo(BuildContext context, Widget icon, String title, String value,
      {bool isMiddle = false}) {
    return Container(
      alignment: Alignment.center,
      padding: 6.paddingH,
      decoration: BoxDecoration(
          border: !isMiddle
              ? null
              : Border(
                  left: BorderSide(
                      color: context.color.mutedContrastColor, width: 1),
                  right: BorderSide(
                      color: context.color.mutedContrastColor, width: 1),
                )),
      constraints: BoxConstraints(
        minWidth: (context.width - 52) / 3,
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          icon,
          4.toWidth,
          RichText(
            text: TextSpan(text: title, style: context.bodySmall, children: [
              TextSpan(
                  text: " $value",
                  style: context.bodySmall?.bold.copyWith(
                    color: primaryColor,
                  ))
            ]),
          )
        ],
      ),
    );
  }
}
