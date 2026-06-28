import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/phone_field.dart';
import 'package:prohand/utils/components/area_dropdown.dart';
import 'package:prohand/utils/components/city_dropdown.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/state_dropdown.dart';
import 'package:prohand/view_models/add_edit_address_view_model/add_edit_address_view_model.dart';
import 'package:prohand/views/add_edit_address_view/components/address_type_buttons.dart';

import '../../utils/components/custom_button.dart';

class AddEditAddressView extends StatelessWidget {
  const AddEditAddressView({super.key});

  @override
  Widget build(BuildContext context) {
    final aea = AddEditAddressViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.addAddress),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            16.toHeight,
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              color: context.color.accentContrastColor,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  FieldWithLabel(
                    label: LocalKeys.title,
                    hintText: LocalKeys.enterTitle,
                    isRequired: true,
                  ),
                  StatesDropdown(
                    stateNotifier: aea.selectedState,
                    isRequired: true,
                  ),
                  CityDropdown(
                    stateNotifier: aea.selectedState,
                    cityNotifier: aea.selectedCity,
                    isRequired: true,
                  ),
                  AreaDropdown(
                    cityNotifier: aea.selectedCity,
                    areaNotifier: aea.selectedArea,
                    isRequired: true,
                  ),
                  FieldWithLabel(
                    label: LocalKeys.zipCode,
                    hintText: LocalKeys.enterZipCode,
                    isRequired: true,
                  ),
                  PhoneField(
                    phone: aea.phone,
                    controller: aea.phoneController,
                    hintText: LocalKeys.enterPhone,
                  ),
                  PhoneField(
                    phone: aea.emergencyPhone,
                    controller: aea.emergencyPhoneController,
                    hintText: LocalKeys.enterPhone,
                    label: LocalKeys.emergencyPhoneNumber,
                  ),
                  const AddressTypeButtons(),
                ],
              ),
            )
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: CustomButton(
            onPressed: () {
              aea.tryAddingAddress(context);
            },
            btText: LocalKeys.saveChanges),
      ),
    );
  }
}
