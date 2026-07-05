import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/image_assets.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/choose_location_view/components/location_search_field.dart';
import 'package:provider/provider.dart';

import '../../helper/svg_assets.dart';
import '../../services/google_location_search_service.dart';
import '../../services/theme_service.dart';
import '../../utils/components/custom_future_widget.dart';
import '../../view_models/add_edit_address_view_model/add_edit_address_view_model.dart';
import 'components/choose_location_buttons.dart';

class ChooseLocationView extends StatelessWidget {
  ChooseLocationView({super.key});
  GoogleMapController? controller;
  Position? currentLoc;
  String? dark;

  @override
  Widget build(BuildContext context) {
    final aea = AddEditAddressViewModel.instance;

    Timer? timer;
    return Consumer<GoogleLocationSearch>(builder: (context, gl, child) {
      return Scaffold(
          appBar: AppBar(
            leading: const NavigationPopIcon(),
            title: Text(
              LocalKeys.choseLocation,
            ),
          ),
          body: Hero(
            tag: "map",
            child: CustomFutureWidget(
              function: gl.isLoading || gl.geoLoc != null || currentLoc != null
                  ? null
                  : _getCurrentLoc(
                      isDark: Provider.of<ThemeService>(context, listen: false)
                          .darkTheme),
              shimmer: SizedBox(
                width: double.infinity,
                child: ImageAssets.mapLight.toAImage(fit: BoxFit.fitWidth).shim,
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: Stack(
                      children: [
                        GoogleMap(
                          onMapCreated: (mapController) {
                            controller = mapController;
                            if (dark != null) {
                              controller?.setMapStyle(dark);
                            }
                          },
                          myLocationEnabled: true,
                          myLocationButtonEnabled: false,
                          zoomControlsEnabled: false,
                          initialCameraPosition: CameraPosition(
                            target: LatLng(
                                gl.geoLoc?.lat ??
                                    currentLoc?.latitude ??
                                    23.75617346773963,
                                gl.geoLoc?.lng ??
                                    currentLoc?.longitude ??
                                    90.441897487471404),
                            zoom: 16.0,
                          ),
                          onCameraMove: (position) {
                            timer?.cancel();
                            timer = Timer(
                              1.seconds,
                              () {
                                gl.fetchGEOLocations(
                                  lat: position.target.latitude,
                                  lng: position.target.longitude,
                                );
                                aea.controller?.animateCamera(
                                  CameraUpdate.newCameraPosition(CameraPosition(
                                    target: LatLng(gl.geoLoc!.lat!, gl.geoLoc!.lng!),
                                    zoom: 16.0,
                                  )),
                                );
                              },
                            );
                          },
                        ),
                        Align(
                          alignment: Alignment.center,
                          child: Padding(
                            padding: const EdgeInsets.only(bottom: 48),
                            child: SvgAssets.mapLongPin.toSVGSized(62),
                          ),
                        ),
                        LocationSearchField(googleMapController: controller),
                      ],
                    ),
                  ),
                  const ChooseLocationButtons(),
                ],
              ),
            ),
          ));
    });
  }

  _getCurrentLoc({isDark}) async {
    final GeolocatorPlatform geolocatorPlatform = GeolocatorPlatform.instance;
    await geolocatorPlatform.requestPermission();
    currentLoc = await geolocatorPlatform.getCurrentPosition();
    if (isDark) {
      dark = await rootBundle.loadString("assets/files/dark-map.json");
    }
    await Future.delayed(1.seconds);
  }
}
