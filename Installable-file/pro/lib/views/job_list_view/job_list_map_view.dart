import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:flutter_svg/svg.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/models/job_models/job_list_model.dart';
import 'package:prohand/services/job_services/job_list_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/views/job_list_view/components/job_tile_sheet.dart';

class JobListMapView extends StatelessWidget {
  final JobListService jl;
  JobListMapView({super.key, required this.jl});

  LatLng? firstLocation;
  ValueNotifier<Map<MarkerId, Marker>> mark = ValueNotifier({});
  final Map<MarkerId, Marker> _markers = {};
  Map<MarkerId, Marker> get markers => _markers;
  final jobsLatLng = {};
  GoogleMapController? mapController;

  Future<void> getMarkers(BuildContext context) async {
    final markerIcon = await BitmapDescriptor.asset(
        const ImageConfiguration(), "assets/images/marker.png");

    for (Job e in jl.jobListModel.jobs) {
      if (e.jobLocation == null) continue;
      if (e.jobLocation!.latitude == null || e.jobLocation!.longitude == null) {
        continue;
      }
      final markerId = MarkerId(e.latLng.toString());
      final latLng = LatLng(
          e.jobLocation!.latitude ?? 0.0, e.jobLocation!.longitude ?? 0.0);
      _markers.putIfAbsent(
          markerId,
          () => Marker(
                markerId: markerId,
                position: latLng,
                icon: markerIcon,
                onTap: () {
                  showModalBottomSheet(
                    context: context,
                    isScrollControlled: true,
                    backgroundColor: context.color.accentContrastColor,
                    builder: (context) {
                      mapController?.animateCamera(
                          CameraUpdate.newLatLngZoom(latLng, 13.0));
                      return JobTileSheet(
                          jobs: jl.jobListModel.jobs
                              .where((job) => job.latLng == e.latLng)
                              .toList());
                    },
                  );
                },
              ));
      firstLocation ??= latLng;
      await Future.delayed(300.milliseconds);
      mark.value = markers;
    }
  }

  @override
  Widget build(BuildContext context) {
    return CustomFutureWidget(
      function: getMarkers(context),
      shimmer: SizedBox(
        width: double.infinity,
        child: FittedBox(
          fit: BoxFit.cover,
          child: SvgPicture.asset(
            "assets/svg/${context.darkTheme ? "map_dark" : "map_light"}.svg",
          ),
        ),
      ),
      child: ValueListenableBuilder(
        valueListenable: mark,
        builder: (context, value, child) {
          debugPrint(value.toString());
          debugPrint(Set<Marker>.of(value.values).length.toString());
          return GoogleMap(
            initialCameraPosition: CameraPosition(
              target: firstLocation ??
                  const LatLng(23.75617346773963, 90.441897487471404),
              zoom: 1.0,
            ),
            zoomControlsEnabled: false,
            onMapCreated: (controller) {
              mapController = controller;
            },
            markers: Set<Marker>.of(value.values),
            buildingsEnabled: false,
            mapToolbarEnabled: true,
            indoorViewEnabled: false,
            liteModeEnabled: false,
            rotateGesturesEnabled: false,
            myLocationButtonEnabled: true,
            myLocationEnabled: true,
            mapType: MapType.normal,
          );
        },
      ),
    );
  }
}
