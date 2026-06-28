import 'package:card_swiper/card_swiper.dart';
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';
import 'package:prohand/utils/components/image_view.dart';

import '../../../utils/components/custom_network_image.dart';

class ServiceDetailsImages extends StatelessWidget {
  final ServiceDetailsService sd;
  const ServiceDetailsImages({super.key, required this.sd});

  @override
  Widget build(BuildContext context) {
    ValueNotifier<int> index = ValueNotifier(0);
    final SwiperController swipeController = SwiperController();
    final serviceDetails = sd.serviceDetailsModel;
    final galleryImages =
        sd.serviceDetailsModel.allServices?.galleryImages ?? [];
    return ValueListenableBuilder(
        valueListenable: index,
        builder: (context, ind, child) {
          return SizedBox(
            width: double.infinity,
            child: Stack(
              children: [
                Swiper(
                  itemCount: galleryImages.isEmpty ? 1 : galleryImages.length,
                  autoplay: false,
                  controller: swipeController,
                  onIndexChanged: (value) {
                    index.value = value;
                  },
                  onTap: (im) {
                    context.toPage(ImageView(galleryImages[im]));
                  },
                  itemBuilder: (context, i) {
                    final image = galleryImages.isEmpty
                        ? [serviceDetails.allServices?.image ?? ""][i]
                        : galleryImages[i];
                    return GestureDetector(
                      onTap: () {
                        context.toPage(ImageView(
                          image,
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
                ),
                GestureDetector(
                  onTap: () {
                    if (galleryImages.isEmpty &&
                        serviceDetails.allServices?.image != null) {
                      context.toPage(
                          ImageView(serviceDetails.allServices!.image!));
                      return;
                    }
                    context.toPage(ImageView(galleryImages[ind]));
                  },
                  child: Container(
                    width: double.infinity,
                  ),
                ),
                Positioned(
                  bottom: 0,
                  child: SingleChildScrollView(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 24, vertical: 8),
                    scrollDirection: Axis.horizontal,
                    child: Wrap(
                      spacing: 6,
                      children: List.generate(galleryImages.length, (i) {
                        return GestureDetector(
                          onTap: () {
                            index.value = i;
                            swipeController.move(i);
                          },
                          child: SquircleContainer(
                            height: 60,
                            width: 80,
                            radius: 12,
                            borderColor: i == ind ? primaryColor : null,
                            borderWidth: 4,
                            child: CustomNetworkImage(
                              height: 60,
                              width: 80,
                              radius: 12,
                              imageUrl: galleryImages[i],
                              fit: BoxFit.cover,
                            ),
                          ),
                        );
                      }),
                    ),
                  ),
                )
              ],
            ),
          );
        });
  }
}
