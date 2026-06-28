import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/field_label.dart';
import 'package:prohandy_client/view_models/filter_view_model/filter_view_model.dart';

class FilterPriceRange extends StatelessWidget {
  const FilterPriceRange({super.key});

  @override
  Widget build(BuildContext context) {
    final fvm = FilterViewModel.instance;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FieldLabel(label: LocalKeys.priceRange),
        ValueListenableBuilder(
          valueListenable: fvm.priceRange,
          builder: (context, range, child) {
            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SliderTheme(
                    data: SliderThemeData.fromPrimaryColors(
                            primaryColor: primaryColor,
                            primaryColorDark: primaryColor,
                            primaryColorLight: primaryColor,
                            valueIndicatorTextStyle: Theme.of(context)
                                .textTheme
                                .bodyMedium!
                                .copyWith(
                                    color: context.color.accentContrastColor))
                        .copyWith(),
                    child: RangeSlider(
                      values: range,
                      max: 5000,
                      divisions: 5000,
                      labels: RangeLabels(
                        range.start.toStringAsFixed(0).cur,
                        range.end.toStringAsFixed(0).cur,
                      ),
                      onChanged: (RangeValues values) {
                        fvm.priceRange.value = values;
                      },
                    )),
              ],
            );
          },
        )
      ],
    );
  }
}
