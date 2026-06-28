import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

class ServiceAreaViewModel {
  final ValueNotifier<bool> disableScroll = ValueNotifier(false);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  final GlobalKey<FormState> formKey = GlobalKey();
  GoogleMapController? controller;

  final ScrollController scrollController = ScrollController();

  ServiceAreaViewModel._init();
  static ServiceAreaViewModel? _instance;
  static ServiceAreaViewModel get instance {
    _instance ??= ServiceAreaViewModel._init();
    return _instance!;
  }

  ServiceAreaViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
