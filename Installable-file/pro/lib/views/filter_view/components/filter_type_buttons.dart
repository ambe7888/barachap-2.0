import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:prohand/view_models/filter_view_model/filter_view_model.dart';

class FilterTypeButton extends StatelessWidget {
  const FilterTypeButton({super.key});

  @override
  Widget build(BuildContext context) {
    final fvm = FilterViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: fvm.selectedType,
      builder: (context, value, child) {
        return Row(
          children: [
            Expanded(
              child: _button(
                  isSelected: value == SearchType.service,
                  title: LocalKeys.service,
                  searchType: SearchType.service),
            ),
            12.toWidth,
            Expanded(
              child: _button(
                  isSelected: value == SearchType.provider,
                  title: LocalKeys.handyman,
                  searchType: SearchType.provider),
            ),
          ],
        );
      },
    );
  }

  _button(
      {required String title,
      required SearchType searchType,
      bool isSelected = false}) {
    return isSelected
        ? CustomButton(onPressed: () {}, btText: title, isLoading: false)
        : OutlinedButton(
            onPressed: () {
              FilterViewModel.instance.selectedType.value = searchType;
            },
            child: Text(title),
          );
  }
}
