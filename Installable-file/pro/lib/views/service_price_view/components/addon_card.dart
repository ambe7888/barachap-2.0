import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../../services/service_services/add_edit_service_service.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import 'add_edit_addon_sheet.dart';

class AddonCard extends StatelessWidget {
  final String title;
  final String price;
  final String? desc;
  final id;
  const AddonCard(
      {super.key,
      required this.title,
      required this.price,
      this.id,
      required this.desc});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTapDown: (details) {
        Alerts.showPopupMenu(context, details, {
          "edit": LocalKeys.edit,
          "remove": LocalKeys.remove,
        }, (value) {
          switch (value) {
            case "edit":
              final titleCon = TextEditingController();
              final priceCon = TextEditingController();
              final descCon = TextEditingController();
              titleCon.text = title;
              priceCon.text = price.tryToParse.toString();
              descCon.text = desc ?? "";
              showModalBottomSheet(
                context: context,
                isScrollControlled: true,
                backgroundColor: context.color.cardFillColor,
                builder: (context) {
                  return AddEditAddonSheet(
                    id: id,
                    titleCon: titleCon,
                    priceCon: priceCon,
                    descCon: descCon,
                  );
                },
              ).then((result) {
                if (result != true) return;
                Provider.of<AddEditServiceService>(context, listen: false)
                    .refresh();
              });

              break;
            case "remove":
              AddEditServiceViewModel.instance.addEditRemoveAddons(id);
              Provider.of<AddEditServiceService>(context, listen: false)
                  .refresh();
              break;
            default:
          }
        });
      },
      child: SquircleContainer(
          width: (context.width - 56) / 1.7,
          height: 100,
          padding: 10.paddingAll,
          radius: 10,
          borderColor: context.color.primaryBorderColor,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                  flex: 1,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        title,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: context.titleSmall?.bold.copyWith(),
                      ),
                      4.toHeight,
                      Text(
                        desc ?? "---",
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: context.bodySmall,
                      ),
                      8.toHeight,
                      Text(
                        price,
                        style: context.bodySmall?.bold.copyWith(
                          color: primaryColor,
                        ),
                      ),
                    ],
                  )),
              Icon(
                Icons.more_vert_rounded,
                color: context.color.tertiaryContrastColo,
                size: 20,
              )
            ],
          )),
    );
  }
}
