import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/view_models/add_edit_address_view_model/add_edit_address_view_model.dart';

import '../../../helper/constant_helper.dart';

class AddressTypeButtons extends StatelessWidget {
  const AddressTypeButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final aea = AddEditAddressViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: aea.selectedType,
      builder: (context, value, child) {
        return Row(
          children: [
            Expanded(
              child: _button(
                  isSelected: value == AddressType.home,
                  title: LocalKeys.home,
                  searchType: AddressType.home,
                  icon: SvgAssets.addressHome),
            ),
            12.toWidth,
            Expanded(
              child: _button(
                  isSelected: value == AddressType.office,
                  title: LocalKeys.office,
                  searchType: AddressType.office,
                  icon: SvgAssets.addressOffice),
            ),
          ],
        );
      },
    );
  }

  _button(
      {required String title,
      required AddressType searchType,
      bool isSelected = false,
      required String icon}) {
    return isSelected
        ? ElevatedButton.icon(
            onPressed: () {
              AddEditAddressViewModel.instance.selectedType.value = searchType;
            },
            label: Text(title),
            icon: icon.toSVGSized(
              18,
              color: color.accentContrastColor,
            ),
          )
        : OutlinedButton.icon(
            onPressed: () {
              AddEditAddressViewModel.instance.selectedType.value = searchType;
            },
            label: Text(title),
            icon: icon.toSVGSized(18),
          );
  }
}
