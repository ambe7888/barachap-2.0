import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/area_dropdown.dart';
import 'package:prohand/utils/components/city_dropdown.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/utils/components/state_dropdown.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';

import '../../view_models/service_area_view_model/service_area_view_model.dart';
import 'components/map_choose_block.dart';

class SignUpServiceArea extends StatelessWidget {
  final bool fromSettings;
  const SignUpServiceArea({super.key, this.fromSettings = false});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    final sam = ServiceAreaViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: fromSettings ? const NavigationPopIcon() : const SizedBox(),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {},
        child: CustomFutureWidget(
          child: ValueListenableBuilder(
              valueListenable: sam.disableScroll,
              builder: (context, dv, child) {
                return SingleChildScrollView(
                  controller: sam.scrollController,
                  physics: dv
                      ? const NeverScrollableScrollPhysics()
                      : const AlwaysScrollableScrollPhysics(),
                  padding:
                      const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        LocalKeys.serviceArea,
                        style: context.labelLarge?.bold,
                      ),
                      4.toHeight,
                      Text(
                        LocalKeys.chooseYourServiceArea,
                        style: context.bodyMedium,
                      ),
                      32.toHeight,
                      Hero(tag: "map", child: MapChooseBlock()),
                      4.toHeight,
                      Text(
                        LocalKeys.mapMoveNote,
                        style: context.bodySmall,
                      ),
                      16.toHeight,
                      FieldWithLabel(
                        label: LocalKeys.address,
                        hintText: LocalKeys.enterAddress,
                        controller: pem.addressController,
                        maxLines: 1,
                        isRequired: true,
                        validator: (value) {
                          if ((value ?? "").trim().isEmpty) {
                            return LocalKeys.enterAddress;
                          }
                          return null;
                        },
                      ),
                      FieldWithLabel(
                        label: LocalKeys.zipCode,
                        hintText: LocalKeys.enterZipCode,
                        controller: pem.zipController,
                        isRequired: true,
                        validator: (value) {
                          if ((value ?? "").trim().isEmpty) {
                            return LocalKeys.enterZipCode;
                          }
                          return null;
                        },
                      ),
                      StatesDropdown(stateNotifier: pem.selectedState),
                      CityDropdown(
                        stateNotifier: pem.selectedState,
                        cityNotifier: pem.selectedCity,
                      ),
                      AreaDropdown(
                        areaNotifier: pem.selectedArea,
                        cityNotifier: pem.selectedCity,
                      ),
                      4.toHeight,
                    ],
                  ),
                );
              }),
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ValueListenableBuilder(
            valueListenable: pem.isLoading,
            builder: (context, value, child) {
              return CustomButton(
                onPressed: () {
                  if (pem.selectedState.value == null) {
                    LocalKeys.selectState.showToast();
                    return;
                  }
                  pem.updateServiceArea(context);
                },
                btText: LocalKeys.continueO,
                isLoading: value,
              );
            }),
      ),
    );
  }
}
