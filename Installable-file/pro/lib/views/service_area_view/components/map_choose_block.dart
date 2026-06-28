import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:provider/provider.dart';

import '../../../helper/image_assets.dart';
import '../../../helper/svg_assets.dart';
import '../../../services/google_location_search_service.dart';
import '../../../utils/components/custom_future_widget.dart';
import '../../../view_models/profile_edit_view_model/profile_edit_view_model.dart';
import '../../../view_models/service_area_view_model/service_area_view_model.dart';
import '../../choose_location_view/choose_location_view.dart';
import './../../../helper/extension/context_extension.dart';
import './../../../helper/extension/string_extension.dart';
import './../../../services/theme_service.dart';
import './../../../utils/components/custom_preloader.dart';
import './../../../utils/components/custom_squircle_widget.dart';

class MapChooseBlock extends StatelessWidget {
  MapChooseBlock({super.key});
  ValueNotifier<Position?> currentLoc = ValueNotifier(null);

  @override
  Widget build(BuildContext context) {
    Timer? timer;
    final darkTheme =
        Provider.of<ThemeService>(context, listen: false).darkTheme;
    final sam = ServiceAreaViewModel.instance;
    final pem = ProfileEditViewModel.instance;
    debugPrint(
        "Current location is ----------------- ${pem.location.value?.lng}"
            .toString());
    return SquircleContainer(
        radius: 20,
        borderColor: context.color.primaryBorderColor,
        height: 300,
        width: double.infinity,
        child: Consumer<GoogleLocationSearch>(builder: (context, gl, child) {
          return GestureDetector(
            onTapDown: gl.isLoading
                ? null
                : (_) {
                    sam.disableScroll.value = true;
                  },
            onTapCancel: gl.isLoading
                ? null
                : () {
                    sam.disableScroll.value = false;
                  },
            child: CustomFutureWidget(
              function:
                  pem.location.value?.lat != null || currentLoc.value != null
                      ? null
                      : _getCurrentLoc(gl, isDark: darkTheme),
              shimmer: SizedBox(
                width: double.infinity,
                child: (darkTheme ? ImageAssets.mapDark : ImageAssets.mapLight)
                    .toAImage(fit: BoxFit.fitWidth),
              ),
              child: Stack(
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(20),
                    child: GoogleMap(
                      initialCameraPosition: CameraPosition(
                        target: LatLng(
                            pem.location.value?.lat ??
                                currentLoc.value?.latitude ??
                                23.75617346773963,
                            pem.location.value?.lng ??
                                currentLoc.value?.longitude ??
                                90.441897487471404),
                        zoom: 16.0,
                      ),
                      zoomControlsEnabled: false,
                      onMapCreated: (controller) {
                        pem.mapController = controller;
                        if (currentLoc.value != null) {
                          debugPrint(
                              "Current location is ----------------- ${pem.location.value}"
                                  .toString());
                          // controller
                          //     .animateCamera(CameraUpdate.newCameraPosition(
                          //   CameraPosition(
                          //     target: LatLng(
                          //         pem.location.value?.lat ??
                          //             currentLoc.value!.latitude,
                          //         pem.location.value?.lng ??
                          //             currentLoc.value!.longitude),
                          //     zoom: 16,
                          //   ),
                          // ));
                        }
                      },
                      style: gl.dark,
                      buildingsEnabled: false,
                      mapToolbarEnabled: true,
                      indoorViewEnabled: false,
                      liteModeEnabled: false,
                      rotateGesturesEnabled: false,
                      myLocationButtonEnabled: true,
                      myLocationEnabled: true,
                      onCameraMove: (details) {
                        timer?.cancel();
                        timer = Timer(
                          1.seconds,
                          () async {
                            if (gl.geoLoc?.lat.toString() ==
                                    details.target.latitude.toString() &&
                                gl.geoLoc?.lng.toString() ==
                                    details.target.longitude.toString()) {
                              return;
                            }

                            await gl.fetchGEOLocations(
                              lat: details.target.latitude,
                              lng: details.target.longitude,
                            );
                            final pem = ProfileEditViewModel.instance;
                            pem.addressController.text =
                                gl.geoLoc?.description ?? "";
                            pem.location.value = gl.geoLoc;
                          },
                        );
                      },
                      mapType: MapType.normal,
                    ),
                  ),
                  Align(
                    alignment: Alignment.center,
                    child: Container(
                      margin: EdgeInsets.only(bottom: gl.isLoading ? 24 : 48),
                      child: gl.isLoading
                          ? const CustomPreloader()
                          : SvgAssets.mapLongPin.toSVGSized(62),
                    ),
                  ),
                  GestureDetector(
                    onTap: () {
                      context.toPage(ChooseLocationView());
                    },
                    child: Align(
                      alignment: Alignment.bottomRight,
                      child: Padding(
                        padding: const EdgeInsets.all(6),
                        child: Icon(
                          Icons.fullscreen_rounded,
                          color: context.color.tertiaryContrastColo,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        }));
  }

  _getCurrentLoc(GoogleLocationSearch gl, {bool isDark = false}) async {
    final GeolocatorPlatform geolocatorPlatform = GeolocatorPlatform.instance;
    await geolocatorPlatform.requestPermission();
    currentLoc.value = await geolocatorPlatform.getCurrentPosition();
    debugPrint(
        "Current location is ----------------- ${currentLoc.value?.longitude}"
            .toString());
    if (isDark && gl.dark == null) {
      await gl.setDark();
    }
  }
}
