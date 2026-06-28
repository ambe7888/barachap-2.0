import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/utils/components/image_view.dart';

class JobDetailsGallery extends StatelessWidget {
  final List<String> gallery;
  final bool isFromPreview;
  const JobDetailsGallery({
    super.key,
    required this.gallery,
    this.isFromPreview = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(vertical: 16),
      color: context.color.accentContrastColor,
      child: SingleChildScrollView(
        padding: 24.paddingH,
        scrollDirection: Axis.horizontal,
        child: Wrap(
          spacing: 12,
          children: gallery
              .map((image) => GestureDetector(
                    onTap: () {
                      context.toPage(ImageView(image));
                    },
                    child: CustomNetworkImage(
                      imageUrl: image,
                      filePath: isFromPreview ? image : null,
                      width: 96,
                      height: 80,
                      radius: 6,
                      fit: BoxFit.cover,
                    ),
                  ))
              .toList(),
        ),
      ),
    );
  }
}
