import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';

import '/helper/extension/context_extension.dart';
import '../../helper/image_assets.dart';

class EmptyWidget extends StatelessWidget {
  const EmptyWidget({
    super.key,
    required this.title,
    this.differentImage,
    this.physics,
    this.margin,
  });

  final String title;
  final String? differentImage;
  final physics;
  final margin;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: margin ?? const EdgeInsets.all(20),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          borderRadius: BorderRadius.circular(8)),
      child: ListView(
        physics: physics,
        children: [
          Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 24),
                child: (differentImage ?? ImageAssets.emptyList).toAImage(),
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(
                    title,
                    style: context.titleSmall
                        ?.copyWith(color: context.color.secondaryContrastColor)
                        .bold6,
                  ),
                ],
              )
            ],
          )
        ],
      ),
    );
  }
}
