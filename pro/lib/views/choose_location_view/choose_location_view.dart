import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/image_assets.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/choose_location_view/components/location_search_field.dart';
import 'package:provider/provider.dart';

import '../../helper/svg_assets.dart';
import '../../services/google_location_search_service.dart';
import '../../utils/components/custom_future_widget.dart';
import 'components/choose_location_buttons.dart';

class ChooseLocationView extends StatelessWidget {
  ChooseLocationView({super.key});
  GoogleMapController? controller;
  Position? currentLoc;

  @override
  Widget build(BuildContext context) {
    Timer? timer;
    return ChangeNotifierProvider<GoogleLocationSearch>(
      create: (context) => GoogleLocationSearch(),
      child: Scaffold(
          appBar: AppBar(
            leading: const NavigationPopIcon(),
            title: Text(
              LocalKeys.choseLocation,
            ),
          ),
          body: CustomFutureWidget(
            function: _getCurrentLoc(),
            shimmer: SizedBox(
              width: double.infinity,
              child: ImageAssets.mapLight.toAImage(fit: BoxFit.fitWidth).shim,
            ),
            child:
                Consumer<GoogleLocationSearch>(builder: (context, gl, child) {
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    child: Stack(
                      children: [
                        GoogleMap(
                          initialCameraPosition: CameraPosition(
                            target: LatLng(
                                currentLoc?.latitude ?? 23.75617346773963,
                                currentLoc?.longitude ?? 90.441897487471404),
                            zoom: 16.0,
                          ),
                          zoomControlsEnabled: false,
                          onMapCreated: (controller) {
                            this.controller = controller;
                          },
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
                              () {
                                gl.fetchGEOLocations(
                                  lat: details.target.latitude,
                                  lng: details.target.longitude,
                                );
                              },
                            );
                          },
                          mapType: MapType.normal,
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
              );
            }),
          )),
    );
  }

  _getCurrentLoc() async {
    final GeolocatorPlatform geolocatorPlatform = GeolocatorPlatform.instance;
    await geolocatorPlatform.requestPermission();
    currentLoc = await geolocatorPlatform.getCurrentPosition();
    await Future.delayed(1.seconds);
  }
}
