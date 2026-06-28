import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/svg_assets.dart';

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
