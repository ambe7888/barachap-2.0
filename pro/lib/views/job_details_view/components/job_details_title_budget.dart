import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import '../../../utils/components/currency_icon.dart';
import '../../job_list_view/components/job_tile_date_type.dart';

class JobDetailsTitleBudget extends StatelessWidget {
  final String title;
  final num budget;
  final EdgeInsetsGeometry? padding;
  final DateTime createdAt;
  final String category;
  const JobDetailsTitleBudget({
    super.key,
    required this.title,
    required this.budget,
    this.padding,
    required this.createdAt,
    required this.category,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding:
          padding ?? const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: context.titleMedium?.bold,
          ),
          4.toHeight,
          JobTileDateType(date: createdAt, category: category),
          12.toHeight,
          Wrap(
            spacing: 4,
            crossAxisAlignment: WrapCrossAlignment.center,
            children: [
              const CurrencyIcon(
                height: 28,
              ),
              Text(
                budget.cur,
                style: context.titleSmall?.bold6,
              )
            ],
          )
        ],
      ),
    );
  }
}
