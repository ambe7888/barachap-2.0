import 'dart:io';

import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:photo_view/photo_view.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';

class ImageView extends StatelessWidget {
  final String imageUrl;
  const ImageView(this.imageUrl, {super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          leading: const NavigationPopIcon(),
        ),
        body: Center(
          child: PhotoView(
            backgroundDecoration:
                const BoxDecoration(color: Colors.transparent),
            minScale: PhotoViewComputedScale.contained,
            maxScale: PhotoViewComputedScale.covered * 2.5,
            loadingBuilder:
                (BuildContext context, ImageChunkEvent? loadingProgress) {
              return LottieBuilder.asset("assets/animations/image_loader.json");
            },
            errorBuilder: (context, exception, stackTrace) {
              return Text(LocalKeys.file);
            },
            imageProvider: imageUrl.contains('http')
                ? NetworkImage(imageUrl) as ImageProvider<Object>?
                : FileImage(File(imageUrl)),
          ),
        ));
  }
}
