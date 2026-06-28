import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/image_assets.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/view_models/filter_view_model/filter_view_model.dart';
import 'package:prohandy_client/views/filter_view/filter_view.dart';
import 'package:prohandy_client/views/service_result_view/service_result_view.dart';
import 'package:provider/provider.dart';

import '../../../services/service/services_search_service.dart';

class HomeViewHeader extends StatelessWidget {
  const HomeViewHeader({super.key});

  @override
  Widget build(BuildContext context) {
    final fvm = FilterViewModel.instance;
    return Container(
      padding: const EdgeInsets.all(24),
      width: context.width,
      decoration: BoxDecoration(
        color: primaryColor,
        image: DecorationImage(
            image: ImageAssets.homeHeaderShade.toAsset, fit: BoxFit.cover),
      ),
      child: Stack(
        children: [
          Column(
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              AppBar(
                backgroundColor: Colors.transparent,
                surfaceTintColor: Colors.transparent,
              ),
              Container(
                decoration: ShapeDecoration(
                  color: context.color.accentContrastColor,
                  shape: SmoothRectangleBorder(
                    borderRadius: SmoothBorderRadius(
                      cornerRadius: 22,
                      cornerSmoothing: 0.5,
                    ),
                  ),
                ),
                child: TextFormField(
                  controller: fvm.searchController,
                  textInputAction: TextInputAction.search,
                  decoration: InputDecoration(
                    fillColor: context.color.accentContrastColor,
                    border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(100),
                        borderSide: BorderSide(
                            color: context.color.accentContrastColor)),
                    enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(100),
                        borderSide: BorderSide(
                            color: context.color.accentContrastColor)),
                    focusedBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(100),
                        borderSide: BorderSide(
                            color: context.color.accentContrastColor)),
                    prefixIcon: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        FittedBox(
                          child: Row(
                            children: [
                              4.toWidth,
                              SvgAssets.search
                                  .toSVGSized(24, color: primaryColor),
                            ],
                          ),
                        )
                      ],
                    ),
                    suffixIcon: GestureDetector(
                      onTap: () {
                        FilterViewModel.dispose;

                        showModalBottomSheet(
                          context: context,
                          isScrollControlled: true,
                          backgroundColor: context.color.accentContrastColor,
                          builder: (context) {
                            return const FilterView();
                          },
                        ).then(
                          (value) {
                            if (value != true) return;

                            Navigator.of(context).push(MaterialPageRoute(
                              builder: (context) => const ServiceResultView(),
                            ));
                          },
                        );
                      },
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          FittedBox(
                            child: Row(
                              children: [
                                Container(
                                  color: context.color.mutedContrastColor,
                                  width: 2,
                                  height: 24,
                                ),
                                Container(
                                  padding:
                                      const EdgeInsets.symmetric(horizontal: 6),
                                  color: Colors.transparent,
                                  child: SvgAssets.filter
                                      .toSVGSized(24, color: primaryColor),
                                ),
                                6.toWidth
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                  ),
                  onFieldSubmitted: (value) {
                    Provider.of<ServicesSearchService>(context, listen: false)
                        .setFilters();
                    Provider.of<ServicesSearchService>(context, listen: false)
                        .setSearchTitle(fvm.searchController.text);
                    Navigator.of(context).push(MaterialPageRoute(
                      builder: (context) => const ServiceResultView(),
                    ));
                  },
                  onTapOutside: (event) {
                    context.unFocus;
                  },
                ),
              )
            ],
          ),
        ],
      ),
    );
  }
}
