import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/view_models/profile_edit_view_model/profile_edit_view_model.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/alerts.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../utils/components/image_view.dart';

class StoreImages extends StatelessWidget {
  const StoreImages({super.key});

  @override
  Widget build(BuildContext context) {
    final pem = ProfileEditViewModel.instance;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        FieldLabel(label: LocalKeys.addImages),
        Text(
          LocalKeys.addPhotosRelatedToYou,
          style: context.titleSmall,
        ),
        12.toHeight,
        ValueListenableBuilder(
            valueListenable: pem.selectedGallery,
            builder: (context, gallery, child) {
              return SingleChildScrollView(
                child: Wrap(
                  spacing: 8,
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
                                pem.removeFromGallery(image);
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
                    ValueListenableBuilder(
                        valueListenable: pem.imageLoading,
                        builder: (context, iLoading, child) {
                          return GestureDetector(
                            onTap: iLoading
                                ? null
                                : () {
                                    pem.selectGalleryImage();
                                  },
                            child: SquircleContainer(
                                width: (context.width - 72) / 3,
                                height: ((context.width - 72) / 3) * .8,
                                radius: 10,
                                color: primaryColor.withOpacity(.2),
                                child: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    iLoading
                                        ? const CustomPreloader()
                                        : const Icon(
                                            Icons.add_circle_outline_rounded,
                                            color: primaryColor,
                                          ),
                                    Text(
                                      iLoading
                                          ? LocalKeys.loadingGallery
                                          : LocalKeys.addImage,
                                      style: context.bodyMedium
                                          ?.copyWith(color: primaryColor),
                                    )
                                  ],
                                )),
                          );
                        })
                  ],
                ),
              );
            })
      ],
    );
  }
}
