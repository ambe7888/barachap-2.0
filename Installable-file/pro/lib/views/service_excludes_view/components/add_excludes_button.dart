import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/service_services/add_edit_service_service.dart';
import 'package:prohand/views/service_excludes_view/components/add_excludes_sheet.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';

class AddExcludesButton extends StatelessWidget {
  const AddExcludesButton({super.key});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton.icon(
        onPressed: () {
          final titleCon = TextEditingController();
          final descCon = TextEditingController();
          showModalBottomSheet(
            context: context,
            isScrollControlled: true,
            backgroundColor: context.color.cardFillColor,
            builder: (context) {
              return AddExcludesSheet(
                title: titleCon,
                description: descCon,
              );
            },
          ).then((result) {
            if (result == false) return;
            Provider.of<AddEditServiceService>(context, listen: false)
                .refresh();
          });
        },
        label: Text(LocalKeys.addExcludes),
        icon: const Icon(
          Icons.add_circle_outline_rounded,
        ),
        style: ButtonStyle(
          backgroundColor: WidgetStateProperty.resolveWith((states) {
            return primaryColor.withOpacity(.2);
          }),
          foregroundColor: WidgetStateProperty.resolveWith((states) {
            if (states.contains(WidgetState.disabled)) {
              return context.color.secondaryContrastColor;
            }

            return primaryColor;
          }),
          shape: WidgetStateProperty.resolveWith<OutlinedBorder?>((states) {
            return SmoothRectangleBorder(
              borderRadius: SmoothBorderRadius(
                cornerRadius: 12,
                cornerSmoothing: 1,
              ),
            );
          }),
        ),
      ),
    );
  }
}
