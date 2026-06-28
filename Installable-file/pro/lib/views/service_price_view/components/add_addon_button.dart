import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/service_services/add_edit_service_service.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/views/service_price_view/components/add_edit_addon_sheet.dart';
import 'package:provider/provider.dart';

class AddAddonButton extends StatelessWidget {
  const AddAddonButton({super.key});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        final titleCon = TextEditingController();
        final priceCon = TextEditingController();
        final descCon = TextEditingController();
        showModalBottomSheet(
          context: context,
          isScrollControlled: true,
          backgroundColor: context.color.cardFillColor,
          builder: (context) {
            return AddEditAddonSheet(
              titleCon: titleCon,
              priceCon: priceCon,
              descCon: descCon,
            );
          },
        ).then((result) {
          if (result != true) return;
          Provider.of<AddEditServiceService>(context, listen: false).refresh();
        });
      },
      child: SquircleContainer(
          width: (context.width - 56) / 1.7,
          height: 100,
          padding: 10.paddingAll,
          radius: 10,
          color: mutedPrimaryColor,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Expanded(
                  flex: 1,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        LocalKeys.addAddon,
                        style: context.titleSmall?.bold.copyWith(
                          color: primaryColor,
                        ),
                      ),
                      8.toHeight,
                      const Icon(
                        Icons.add_rounded,
                        color: primaryColor,
                      )
                    ],
                  ))
            ],
          )),
    );
  }
}
