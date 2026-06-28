import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';

import '../../components/custom_network_image.dart';

class ServiceCardImage extends StatelessWidget {
  final String? imageUrl;
  final bool isFavorite;
  const ServiceCardImage({super.key, this.imageUrl, required this.isFavorite});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        CustomNetworkImage(
          width: 188,
          height: 128,
          imageUrl: imageUrl.toString(),
          fit: BoxFit.cover,
          radius: 8,
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.end,
          children: [
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: CircleAvatar(
                radius: 16,
                backgroundColor: context.color.accentContrastColor,
                child: SvgAssets.heart.toSVG,
              ),
            )
          ],
        )
      ],
    );
  }
}
