import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

import '../../helper/local_keys.g.dart';
import '../../utils/components/alerts.dart';
import '../../utils/components/custom_network_image.dart';
import '../../utils/components/custom_squircle_widget.dart';
import '../../utils/components/image_view.dart';
import 'components/selectable_staff.dart';

class ServiceStaffsView extends StatelessWidget {
  const ServiceStaffsView({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Divider(
            height: 8,
            thickness: 8,
            color: context.color.backgroundColor,
          ),
          Padding(
            padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  LocalKeys.serviceStaffAndGallery,
                  style: context.titleLarge?.bold,
                ),
                Text(
                  LocalKeys.serviceStaffAndGalleryDesc,
                  style: context.bodySmall
                      ?.copyWith(color: context.color.primaryContrastColor),
                ),
                24.toHeight,
                Text(
                  LocalKeys.chooseStaff,
                  style: context.titleMedium?.bold,
                ),
                Text(
                  LocalKeys.chooseStaffToAllocate,
                  style: context.titleSmall,
                ),
                12.toHeight,
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: List.generate(12, (order) {
                    return SelectableStaff(
                      isSelected: (order % 3).isEven,
                    );
                  }),
                ),
                32.toHeight,
                Text(
                  LocalKeys.addPhotos,
                  style: context.titleMedium?.bold,
                ),
                Text(
                  LocalKeys.addPhotosRelatedToService,
                  style: context.titleSmall,
                ),
                12.toHeight,
                ValueListenableBuilder(
                    valueListenable: aem.selectedGallery,
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
                                        aem.removeFromGallery(image);
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
                                aem.selectGalleryImage();
                              },
                              child: SquircleContainer(
                                  width: (context.width - 72) / 3,
                                  height: ((context.width - 72) / 3) * .8,
                                  radius: 10,
                                  color: primaryColor.withOpacity(.2),
                                  child: Column(
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
                        ),
                      );
                    })
              ],
            ),
          )
        ],
      ),
    );
  }
}
