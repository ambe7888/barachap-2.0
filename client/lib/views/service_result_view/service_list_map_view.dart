import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/utils/components/custom_future_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:provider/provider.dart';
import 'package:prohandy_client/customizations/colors.dart';

import '../../helper/image_assets.dart';
import '../../models/home_models/services_list_model.dart';
import '../../services/theme_service.dart';
import '../../utils/components/custom_network_image.dart';
import '../../helper/constant_helper.dart';
import 'components/service_tile_sheet.dart';

class ServiceListMapView extends StatelessWidget {
  final List<ServiceModel> serviceList;
  ServiceListMapView({super.key, required this.serviceList});

  LatLng? firstLocation;
  ValueNotifier<Set<Marker>> mark = ValueNotifier({});
  final Set<Marker> _markers = {};
  Set<Marker> get markers => _markers;

  Future<void> getMarkers(BuildContext context) async {
    for (ServiceModel e in serviceList) {
      if ((e.provider?.latitude ?? e.admin?.serviceArea?.latitude) == null ||
          (e.provider?.longitude ?? e.admin?.serviceArea?.longitude) == null) {
        continue;
      }
      final latLng = LatLng(
          (e.provider?.latitude ?? e.admin?.serviceArea?.latitude) ?? 0.0,
          (e.provider?.longitude ?? e.admin?.serviceArea?.longitude) ?? 0.0);
      _markers.add(Marker(
        markerId: MarkerId(e.id.toString()),
        position: latLng,
        onTap: () {
          showModalBottomSheet(
            context: context,
            isScrollControlled: true,
            backgroundColor: context.color.accentContrastColor,
            builder: (context) {
              if (e.provider != null) {
                return ServiceTileSheet(
                    serviceList: serviceList.where((s) {
                  return (s.provider?.id).toString() ==
                      e.provider?.id.toString();
                }).toList());
              }
              return ServiceTileSheet(serviceList: serviceList);
            },
          );
        },
      ));
      firstLocation ??= latLng;
      await Future.delayed(50.milliseconds);
      mark.value = Set.from(markers);
    }
  }


  @override
  Widget build(BuildContext context) {
    final darkTheme =
        Provider.of<ThemeService>(context, listen: false).darkTheme;
    return Scaffold(
      appBar: AppBar(
        leading: NavigationPopIcon(),
      ),
      body: CustomFutureWidget(
        function: getMarkers(context),
        shimmer: SizedBox(
          width: double.infinity,
          child: FittedBox(
            fit: BoxFit.cover,
            child: (darkTheme ? ImageAssets.mapDark : ImageAssets.mapLight)
                .toAImage(fit: BoxFit.fitWidth),
          ),
        ),
        child: ValueListenableBuilder(
          valueListenable: mark,
          builder: (context, value, child) {
            return GoogleMap(
              initialCameraPosition: CameraPosition(
                target: firstLocation ??
                    const LatLng(23.75617346773963, 90.441897487471404),
                zoom: 13.0,
              ),
              markers: value,
              myLocationEnabled: true,
            );
          },
        ),
      ),
    );
  }
}
