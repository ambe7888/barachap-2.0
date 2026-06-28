import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';

import '../../../models/service/service_details_model.dart';
import '../../../utils/components/custom_network_image.dart';

class SelectableStaffTile extends StatelessWidget {
  final id;
  final String name;
  final ValueNotifier<Staff?> valueListenable;
  final String? imageUrl;
  final void Function() onSelect;
  const SelectableStaffTile(
      {super.key,
      this.id,
      required this.name,
      this.imageUrl,
      required this.onSelect,
      required this.valueListenable});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onSelect,
      child: ValueListenableBuilder(
        valueListenable: valueListenable,
        builder: (context, value, child) {
          return Padding(
            padding: 12.paddingV,
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                CustomNetworkImage(
                  height: 40,
                  width: 40,
                  radius: 20,
                  fit: BoxFit.cover,
                  name: name,
                  imageUrl: imageUrl,
                ),
                12.toWidth,
                Expanded(
                  flex: 1,
                  child: Text(
                    name,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: context.titleMedium?.bold,
                  ),
                ),
                12.toWidth,
                Checkbox(
                  value: (value?.id).toString() == id.toString(),
                  onChanged: (value) {
                    onSelect();
                  },
                )
              ],
            ),
          );
        },
      ),
    );
  }
}
