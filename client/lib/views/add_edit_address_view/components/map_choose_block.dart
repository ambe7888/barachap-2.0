import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/services/theme_service.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';

import '../../../helper/image_assets.dart';
import '../../../helper/svg_assets.dart';
import '../../../services/google_location_search_service.dart';
import '../../../utils/components/custom_future_widget.dart';
import '../../../view_models/add_edit_address_view_model/add_edit_address_view_model.dart';
import '../../choose_location_view/choose_location_view.dart';

class MapChooseBlock extends StatelessWidget {
  MapChooseBlock({super.key});
  GoogleMapController? controller;
  ValueNotifier<Position?> currentLoc = ValueNotifier(null);

  @override
  Widget build(BuildContext context) {
    Timer? timer;
    final darkTheme =
        Provider.of<ThemeService>(context, listen: false).darkTheme;
    final aea = AddEditAddressViewModel.instance;
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
                    aea.disableScroll.value = true;
                  },
            onTapCancel: gl.isLoading
                ? null
                : () {
                    aea.disableScroll.value = false;
                  },
            child: CustomFutureWidget(
              function:
                  gl.isLoading || gl.geoLoc != null || currentLoc.value != null
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
                      onMapCreated: (mapController) {
                        controller = mapController;
                        aea.controller = mapController;
                        if (darkTheme && gl.dark != null) {
                          controller?.setMapStyle(gl.dark);
                        }
                      },
                      myLocationEnabled: true,
                      myLocationButtonEnabled: false,
                      zoomControlsEnabled: false,
                      initialCameraPosition: CameraPosition(
                        target: LatLng(
                            gl.geoLoc?.lat ??
                                currentLoc.value?.latitude ??
                                23.75617346773963,
                            gl.geoLoc?.lng ??
                                currentLoc.value?.longitude ??
                                90.441897487471404),
                        zoom: 16.0,
                      ),
                      onCameraMove: (position) {
                        timer?.cancel();
                        timer = Timer(
                          1.seconds,
                          () async {
                            if (gl.geoLoc?.lat.toString() ==
                                    position.target.latitude.toString() &&
                                gl.geoLoc?.lng.toString() ==
                                    position.target.longitude.toString()) {
                              return;
                            }
                            await gl.fetchGEOLocations(
                              lat: position.target.latitude,
                              lng: position.target.longitude,
                            );
                            final aea = AddEditAddressViewModel.instance;
                            aea.addressController.text =
                                gl.geoLoc?.description ?? "";
                          },
                        );
                      },
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
    if (isDark && gl.dark == null) {
      await gl.setDark();
    }
  }
}
