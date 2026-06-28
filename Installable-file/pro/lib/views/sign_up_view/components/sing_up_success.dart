import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';

class SingUpSuccess extends StatelessWidget {
  const SingUpSuccess({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        SvgAssets.doneFilled.toSVGSized(
          100,
          color: context.color.primarySuccessColor,
        )
      ],
    );
  }
}
