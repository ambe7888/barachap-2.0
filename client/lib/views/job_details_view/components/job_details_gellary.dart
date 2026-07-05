import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/utils/components/image_view.dart';
import 'package:prohandy_client/customizations/colors.dart';

class JobDetailsGallery extends StatefulWidget {
  final List<String> gallery;
  final bool isFromPreview;
  const JobDetailsGallery({
    super.key,
    required this.gallery,
    this.isFromPreview = false,
  });

  @override
  State<JobDetailsGallery> createState() => _JobDetailsGalleryState();
}

class _JobDetailsGalleryState extends State<JobDetailsGallery> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    if (widget.gallery.isEmpty) return const SizedBox.shrink();

    return Container(
      width: double.infinity,
      height: 220,
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: context.color.primaryBorderColor.withOpacity(0.6),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Stack(
        alignment: Alignment.bottomCenter,
        children: [
          PageView.builder(
            itemCount: widget.gallery.length,
            onPageChanged: (index) {
              setState(() {
                _currentIndex = index;
              });
            },
            itemBuilder: (context, index) {
              final image = widget.gallery[index];
              return GestureDetector(
                onTap: () {
                  context.toPage(ImageView(image));
                },
                child: Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: CustomNetworkImage(
                    imageUrl: image,
                    filePath: widget.isFromPreview ? image : null,
                    width: double.infinity,
                    height: 200,
                    radius: 8,
                    fit: BoxFit.cover,
                  ),
                ),
              );
            },
          ),
          if (widget.gallery.length > 1)
            Positioned(
              bottom: 16,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: List.generate(
                  widget.gallery.length,
                  (index) => AnimatedContainer(
                    duration: const Duration(milliseconds: 300),
                    margin: const EdgeInsets.symmetric(horizontal: 4),
                    width: _currentIndex == index ? 24 : 8,
                    height: 8,
                    decoration: BoxDecoration(
                      color: _currentIndex == index
                          ? primaryColor
                          : primaryColor.withOpacity(0.3),
                      borderRadius: BorderRadius.circular(4),
                    ),
                  ),
                ),
              ),
            ),
        ],
      ),
    );
  }
}
