import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/view_models/filter_view_model/filter_view_model.dart';

class FilterButtons extends StatelessWidget {
  const FilterButtons({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
            flex: 1,
            child: OutlinedButton(
                onPressed: () {
                  context.pop;

                  FilterViewModel.instance.reset(context);
                },
                child: Text(LocalKeys.resetFilter))),
        16.toWidth,
        Expanded(
            flex: 1,
            child: ElevatedButton(
                onPressed: () {
                  context.popTrue;
                  FilterViewModel.instance.setFilters(context);
                },
                child: Text(LocalKeys.applyFilter))),
      ],
    );
  }
}
