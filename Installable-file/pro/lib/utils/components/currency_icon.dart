import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';

class CurrencyIcon extends StatelessWidget {
  final double? height;
  const CurrencyIcon({super.key, this.height});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: height,
      child: FittedBox(
        child: Container(
          padding: 4.paddingAll,
          decoration: BoxDecoration(
            shape: BoxShape.circle,
            border: Border.all(color: context.color.tertiaryContrastColo),
          ),
          child: Text(context.dProvider.currencySymbol,
              style: context.titleMedium
                  ?.copyWith(fontSize: 10)
                  .bold
                  .copyWith(color: context.color.tertiaryContrastColo)),
        ),
      ),
    );
  }
}
