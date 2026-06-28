import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../service_result_view/service_result_view.dart';

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
                },
                child: Text(LocalKeys.resetFilter))),
        16.toWidth,
        Expanded(
            flex: 1,
            child: ElevatedButton(
                onPressed: () {
                  context.pop;
                  context.toPage(
                    const ServiceResultView(),
                  );
                },
                child: Text(LocalKeys.applyFilter))),
      ],
    );
  }
}
