import 'package:card_swiper/card_swiper.dart';
import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/view_models/service_provider_view_model/service_provider_view_model.dart';
import 'package:youtube_player_iframe/youtube_player_iframe.dart';

import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/image_view.dart';

class ProviderStoreImages extends StatelessWidget {
  final List<String> images;
  final String? videoUrl;
  const ProviderStoreImages({
    super.key,
    required this.images,
    this.videoUrl,
  });

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    return Stack(
      alignment: Alignment.bottomRight,
      children: [
        SizedBox(
          height: context.width * .5,
          child: ValueListenableBuilder(
              valueListenable: spm.showVideo,
              builder: (context, value, child) {
                if (value && videoUrl != null) {
                  final controller = YoutubePlayerController.fromVideoId(
                    videoId: videoUrl!,
                    autoPlay: false,
                    params: const YoutubePlayerParams(
                      showControls: false,
                      showFullscreenButton: true,
                      strictRelatedVideos: true,
                      loop: true,
                      enableCaption: false,
                      showVideoAnnotations: false,
                    ),
                  );
                  return SizedBox(
                    width: double.infinity,
                    child: YoutubePlayer(
                      controller: controller,
                      enableFullScreenOnVerticalDrag: false,
                    ),
                  );
                }
                return Swiper(
                  itemCount: images.isEmpty ? 0 : images.length,
                  autoplay: true,
                  onIndexChanged: (value) {},
                  onTap: (im) {
                    context.toPage(ImageView(images[im]));
                  },
                  itemBuilder: (context, i) {
                    final image = images.isEmpty ? "" : images[i];
                    return GestureDetector(
                      onTap: () {
                        context.toPage(ImageView(
                          image,
                          heroTag: i.toString(),
                        ));
                      },
                      child: Hero(
                        tag: i.toString(),
                        child: CustomNetworkImage(
                          width: double.infinity,
                          imageUrl: image,
                          fit: BoxFit.cover,
                        ),
                      ),
                    );
                  },
                );
              }),
        ),
        if (videoUrl != null)
          ValueListenableBuilder(
              valueListenable: spm.showVideo,
              builder: (context, value, child) {
                return IconButton(
                    onPressed: () {
                      spm.showVideo.value = !spm.showVideo.value;
                    },
                    icon: Icon(
                      value ? Icons.image : Icons.video_collection,
                      size: 42,
                      color: primaryColor,
                    ));
              })
      ],
    );
  }
}
