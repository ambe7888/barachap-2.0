import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:provider/provider.dart';

import '../../services/service_services/add_edit_service_service.dart';
import '../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'components/add_excludes_button.dart';
import 'components/excludes_tile.dart';

class ServiceExcludesView extends StatelessWidget {
  const ServiceExcludesView({super.key});

  @override
  Widget build(BuildContext context) {
    final aes = AddEditServiceViewModel.instance;
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.whatIsNotIncluded,
            style: context.titleLarge?.bold,
          ),
          Text(
            LocalKeys.whatIsNotIncludedDesc,
            style: context.bodySmall
                ?.copyWith(color: context.color.primaryContrastColor),
          ),
          24.toHeight,
          Consumer<AddEditServiceService>(builder: (context, ae, child) {
            return ValueListenableBuilder(
              valueListenable: aes.excludes,
              builder: (context, value, child) => Expanded(
                  child: ListView.separated(
                      padding: const EdgeInsets.symmetric(vertical: 2),
                      itemBuilder: (context, index) {
                        return ExcludesTile(
                          id: index,
                          title: value[index]["exclude_service_title"] ?? "",
                          description:
                              value[index]["exclude_service_description"] ?? "",
                        );
                      },
                      separatorBuilder: (context, index) => 12.toHeight,
                      itemCount: value.length)),
            );
          }),
          24.toHeight,
          const AddExcludesButton(),
        ],
      ),
    );
  }
}
