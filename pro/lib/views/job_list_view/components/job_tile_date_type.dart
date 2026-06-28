import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../../helper/constant_helper.dart';

class JobTileDateType extends StatelessWidget {
  final DateTime date;
  final String category;
  const JobTileDateType(
      {super.key, required this.date, required this.category});

  @override
  Widget build(BuildContext context) {
    return RichText(
        text: TextSpan(
            text:
                "${DateFormat("MMM dd, yyyy", dProvider.languageSlug).format(date)} · ",
            style: context.bodySmall?.copyWith(
              color: context.color.tertiaryContrastColo,
            ),
            children: [
          TextSpan(
              text: category,
              style: context.bodySmall?.bold.copyWith(
                color: primaryColor,
              ),
              children: const [])
        ]));
  }
}
