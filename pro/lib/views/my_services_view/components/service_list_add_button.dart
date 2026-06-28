import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

import '../../add_edit_service_view/add_edit_service_view.dart';

class ServiceListAddButton extends StatelessWidget {
  const ServiceListAddButton({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Expanded(
              flex: 1,
              child: Text(
                LocalKeys.myServices,
                style: context.titleLarge?.bold,
              ),
            ),
            ElevatedButton.icon(
                onPressed: () {
                  AddEditServiceViewModel.dispose;
                  context.toPage(const AddEditServiceView());
                },
                label: Text(LocalKeys.newService),
                icon: Icon(
                  Icons.add_circle_outline_rounded,
                  color: context.color.accentContrastColor,
                ))
          ],
        ),
      ],
    );
  }
}
