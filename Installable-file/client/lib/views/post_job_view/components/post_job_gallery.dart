import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:prohandy_client/utils/components/image_view.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_preloader.dart';
import '../../../view_models/post_job_view_model/post_job_view_model.dart';

class PostJobGallery extends StatelessWidget {
  const PostJobGallery({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          LocalKeys.uploadGalleryPhotos,
          style: context.titleLarge?.bold,
        ),
        6.toHeight,
        Text(
          LocalKeys.uploadPhotosSuggestion,
          style: context.titleSmall,
        ),
        24.toHeight,
        ValueListenableBuilder(
            valueListenable: pjm.imageLoading,
            builder: (context, loading, child) {
              return ValueListenableBuilder(
                valueListenable: pjm.selectedGallery,
                builder: (context, gallery, child) {
                  return Wrap(
                    spacing: 12,
                    runSpacing: 12,
                    children: [
                      ...gallery.map((image) {
                        return GestureDetector(
                          onTapDown: (details) {
                            Alerts.showPopupMenu(context, details, {
                              "view": LocalKeys.view,
                              "remove": LocalKeys.remove,
                            }, (value) {
                              switch (value) {
                                case "view":
                                  context.toPage(ImageView(image.path));
                                  break;
                                case "remove":
                                  pjm.removeFromGallery(image);
                                  break;
                                default:
                              }
                            });
                          },
                          child: CustomNetworkImage(
                            filePath: image.path,
                            width: (context.width - 72) / 3,
                            height: ((context.width - 72) / 3) * .8,
                            radius: 10,
                            fit: BoxFit.cover,
                          ),
                        );
                      }),
                      GestureDetector(
                        onTap: () {
                          if (loading) {
                            LocalKeys.loadingImages.showToast();
                            return;
                          }
                          pjm.selectGalleryImage();
                        },
                        child: SquircleContainer(
                            width: (context.width - 72) / 3,
                            height: ((context.width - 72) / 3) * .8,
                            radius: 10,
                            color: mutedPrimaryColor,
                            child: loading
                                ? const Center(child: CustomPreloader())
                                : Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      const Icon(
                                        Icons.add_circle_outline_rounded,
                                        color: primaryColor,
                                      ),
                                      Text(
                                        LocalKeys.addPhoto,
                                        style: context.bodyMedium
                                            ?.copyWith(color: primaryColor),
                                      )
                                    ],
                                  )),
                      )
                    ],
                  );
                },
              );
            })
      ],
    );
  }
}
