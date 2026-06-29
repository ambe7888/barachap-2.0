import 'package:card_swiper/card_swiper.dart';
import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/services/home_services/service_details_service.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/custom_network_image.dart';
import 'package:prohandy_client/views/intro_view/components/dot_indicator.dart';
import 'package:prohandy_client/views/service_by_category_view/service_by_category_view.dart';
import 'package:prohandy_client/views/service_by_offer_view/service_by_offer_view.dart';
import 'package:provider/provider.dart';

import '../../../services/home_services/home_slider_service.dart';
import '../../service_details_view/service_details_view.dart';
import 'slider_skeleton.dart';

class HomeSlider extends StatelessWidget {
  const HomeSlider({super.key});

  @override
  Widget build(BuildContext context) {
    final index = ValueNotifier(0);
    return Consumer<HomeSliderService>(builder: (context, hs, child) {
      return CustomFutureWidget(
        function: hs.sliderList == null ? hs.fetchHomeSlider() : null,
        shimmer: const SliderSkeleton(),
        child: (hs.sliderList ?? []).isEmpty
            ? const SizedBox()
            : Container(
                padding: const EdgeInsets.symmetric(
                  vertical: 16,
                ),
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                ),
                child: Column(
                  children: [
                    SizedBox(
                      height: ((context.width - 24) / 307) * 150,
                      child: Swiper(
                        itemCount: hs.sliderList!.length,
                        autoplay: true,
                        itemHeight: context.width * 0.82,
                        onIndexChanged: (value) {
                          index.value = value;
                        },
                        itemBuilder: (context, index) {
                          final slider = hs.sliderList![index];
                          return GestureDetector(
                            onTap: () {
                              debugPrint("Slider clicked: identity=${slider.identity}, type=${slider.type}");
                              if (slider.identity == null) return;
                              final type = slider.type?.toLowerCase() ?? "";
                              switch (type) {
                                case "category":
                                  context.toPage(ServiceByCategoryView(
                                    catId: slider.identity,
                                  ));
                                  break;
                                case "offer":
                                  context.toPage(ServiceByOfferView(
                                    offerId: slider.identity,
                                  ));
                                  break;
                                case "service":
                                  context.toPage(
                                      ServiceDetailsView(id: slider.identity),
                                      then: (_) {
                                    Provider.of<ServiceDetailsService>(context,
                                            listen: false)
                                        .remove(slider.identity);
                                  });
                                  break;
                                default:
                                  debugPrint("Unknown slider type: $type");
                              }
                            },
                            child: Padding(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 8.0),
                              child: CustomNetworkImage(
                                width: context.width * 0.82,
                                height: ((context.width - 24) / 307) * 150,
                                radius: 12,
                                imageUrl: slider.image,
                                fit: BoxFit.cover,
                              ),
                            ),
                          );
                        },
                      ),
                    ),
                    16.toHeight,
                    Center(
                      child: ValueListenableBuilder(
                        valueListenable: index,
                        builder: (context, value, child) {
                          return Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: List.generate(
                                hs.sliderList!.length,
                                (i) => DotIndicator(
                                      value == i,
                                      color: primaryColor,
                                      mutedColor:
                                          context.color.mutedContrastColor,
                                    )).toList(),
                          );
                        },
                      ),
                    )
                  ],
                ),
              ),
      );
    });
  }
}
