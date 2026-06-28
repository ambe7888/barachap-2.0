import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/utils/components/state_dropdown.dart';

import '../../../utils/components/city_dropdown.dart';

class FilterLocation extends StatelessWidget {
  const FilterLocation({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FieldLabel(label: LocalKeys.imLookingServiceIn),
        Row(
          children: [
            Expanded(
              flex: 1,
              child: StatesDropdown(
                stateNotifier: ValueNotifier(null),
                hideLabel: true,
              ),
            ),
            12.toWidth,
            Expanded(
              flex: 1,
              child: CityDropdown(
                cityNotifier: ValueNotifier(null),
                stateNotifier: ValueNotifier(null),
                hideLabel: true,
              ),
            )
          ],
        )
      ],
    );
  }
}
