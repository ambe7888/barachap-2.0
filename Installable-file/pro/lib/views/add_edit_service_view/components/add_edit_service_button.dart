import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../helper/local_keys.g.dart';
import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class AddEditServiceButton extends StatelessWidget {
  const AddEditServiceButton({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: aem.pageIndex,
      builder: (context, index, child) {
        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
              color: context.color.accentContrastColor,
              border: Border(
                  top: BorderSide(color: context.color.primaryBorderColor))),
          child: Row(
            children: [
              if (index > 0) ...[
                Expanded(
                  flex: 1,
                  child: OutlinedButton(
                      onPressed: () {
                        aem.goBack(context);
                      },
                      child: Text(LocalKeys.back)),
                ),
                12.toWidth,
              ],
              Expanded(
                flex: 1,
                child: ElevatedButton(
                    onPressed: () {
                      aem.continueForward(context);
                    },
                    child: Text(LocalKeys.continueO)),
              ),
            ],
          ),
        );
      },
    );
  }
}
