import 'dart:io';

import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/services/theme_service.dart';
import 'package:prohandy_client/utils/components/custom_squircle_widget.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';

import '../../../helper/image_assets.dart';
import '../../../helper/local_keys.g.dart';
import '../../../services/google_location_search_service.dart';
import '../../../utils/components/custom_future_widget.dart';
import '../../../view_models/map_location_vew_model/map_single_location_view_model.dart';

class ProviderLocationBlock extends StatelessWidget {
  const ProviderLocationBlock({
    super.key,
    this.lat,
    this.lng,
    required this.title,
  });
  final lat;
  final lng;
  final String title;

  @override
  Widget build(BuildContext context) {
    final darkTheme =
        Provider.of<ThemeService>(context, listen: false).darkTheme;
    final msl = MapSingleLocationViewModel.instance;
    
    // Create marker
    final Set<Marker> _markers = {};
    if (lat != null && lng != null) {
      double lLat = lat is String ? double.parse(lat) : lat;
      double lLng = lng is String ? double.parse(lng) : lng;
      _markers.add(Marker(
        markerId: const MarkerId('providerLocation'),
        position: LatLng(lLat, lLng),
        infoWindow: InfoWindow(title: title),
      ));
    }

    return Stack(
      alignment: Alignment.bottomRight,
      children: [
        SquircleContainer(
            height: 200,
            width: double.infinity,
            child:
                Consumer<GoogleLocationSearch>(builder: (context, gl, child) {
              return CustomFutureWidget(
                shimmer: SizedBox(
                  width: double.infinity,
                  child:
                      (darkTheme ? ImageAssets.mapDark : ImageAssets.mapLight)
                          .toAImage(fit: BoxFit.fitWidth),
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: ValueListenableBuilder(
                      valueListenable: msl.mark,
                      builder: (context, value, child) {
                        if (lat == null || lng == null) return const SizedBox.shrink();
                        double lLat = lat is String ? double.parse(lat) : lat;
                        double lLng = lng is String ? double.parse(lng) : lng;
                        
                        return GoogleMap(
                          initialCameraPosition: CameraPosition(
                            target: LatLng(lLat, lLng),
                            zoom: 16.0,
                          ),
                          markers: _markers,
                          zoomControlsEnabled: false,
                          mapToolbarEnabled: false,
                          myLocationButtonEnabled: false,
                          onMapCreated: (GoogleMapController controller) {
                            msl.controller = controller;
                            if (darkTheme && gl.dark != null) {
                              msl.controller?.setMapStyle(gl.dark);
                            }
                          },
                        );
                      }),
                ),
              );
            })),
        if (Platform.isIOS)
          IconButton(
            icon: Icon(Icons.directions, color: primaryColor, size: 36),
            onPressed: () async {
              LocalKeys.openingMap.showToast();
              debugPrint("opening apple map".toString());
              final url = 'http://maps.apple.com/?ll=$lat,$lng&q=$title';

              if (await canLaunchUrl(Uri.parse(url))) {
                await launchUrl(Uri.parse(url));
              } else {
                throw 'Could not open Apple Maps';
              }
            },
          ),
      ],
    );
  }

  _getCurrentLoc(GoogleLocationSearch gl, {bool isDark = false}) async {
    final GeolocatorPlatform geolocatorPlatform = GeolocatorPlatform.instance;
    await geolocatorPlatform.requestPermission();
    if (isDark && gl.dark == null) {
      await gl.setDark();
    }
  }
}
