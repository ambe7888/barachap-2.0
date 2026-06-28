import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/service_services/add_edit_service_service.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:prohand/views/service_price_view/components/addon_card.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '../../../helper/local_keys.g.dart';
import 'add_addon_button.dart';

class ServiceAddonsAdd extends StatelessWidget {
  const ServiceAddonsAdd({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: aem.enableAddons,
      builder: (context, value, child) {
        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            GestureDetector(
              onTap: () {
                aem.enableAddons.value = !value;
              },
              child: Row(
                children: [
                  Checkbox(
                    value: value,
                    onChanged: (v) {
                      aem.enableAddons.value = !value;
                    },
                  ),
                  4.toWidth,
                  Expanded(
                    flex: 8,
                    child: Text(
                      LocalKeys.iOfferAddons,
                      style: context.titleSmall,
                    ),
                  ),
                ],
              ),
            ),
            if (value) ...[
              12.toHeight,
              Consumer<AddEditServiceService>(builder: (context, ae, child) {
                debugPrint(aem.addons.value.toString());
                return ValueListenableBuilder(
                  valueListenable: aem.addons,
                  builder: (context, addon, child) {
                    return SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: Wrap(
                          spacing: 8,
                          children: [
                            ...List.generate(addon.length, (i) {
                              return AddonCard(
                                title: addon[i]["addon_service_title"],
                                price: (addon[i]["addon_service_price"])
                                    .toString()
                                    .tryToParse
                                    .cur,
                                desc: addon[i]["addon_service_description"],
                                id: i,
                              );
                            }),
                            child!,
                          ],
                        ));
                  },
                  child: const AddAddonButton(),
                );
              })
            ],
          ],
        );
      },
    );
  }
}
