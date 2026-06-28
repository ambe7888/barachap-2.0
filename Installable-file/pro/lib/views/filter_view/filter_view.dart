import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_modal_sheet.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/views/filter_view/components/filter_location.dart';
import 'package:prohand/views/filter_view/components/filter_price_range.dart';
import 'package:prohand/views/filter_view/components/filter_rating_section.dart';
import 'package:prohand/views/filter_view/components/filter_type_buttons.dart';

import 'components/filter_buttons.dart';
import 'components/filter_category_list.dart';
import 'components/filter_units.dart';

class FilterView extends StatelessWidget {
  const FilterView({super.key});

  @override
  Widget build(BuildContext context) {
    return CustomModalSheet(
        maxHeight: context.height / 1.5,
        child: Expanded(
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                16.toHeight,
                FieldLabel(label: LocalKeys.iamLookingFor).hp20,
                8.toHeight,
                const FilterTypeButton().hp20,
                const FilterCategoryList(),
                const FilterRatingSection().hp20,
                24.toHeight,
                const FilterPriceRange().hp20,
                24.toHeight,
                const FilterLocation().hp20,
                12.toHeight,
                const FilterUnits(),
                12.toHeight,
                const FilterButtons().hp20,
                24.toHeight,
              ],
            ),
          ),
        ));
  }
}
