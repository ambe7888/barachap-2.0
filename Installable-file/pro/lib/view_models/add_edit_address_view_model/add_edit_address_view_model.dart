import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:prohand/helper/phone_field.dart';
import 'package:prohand/models/address_models/area_model.dart';
import 'package:prohand/models/address_models/city_model.dart';
import 'package:prohand/models/address_models/states_model.dart';

class AddEditAddressViewModel {
  final TextEditingController titlePassController = TextEditingController();
  final TextEditingController newPassController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController emergencyPhoneController =
      TextEditingController();

  final ValueNotifier<States?> selectedState = ValueNotifier(null);
  final ValueNotifier<City?> selectedCity = ValueNotifier(null);
  final ValueNotifier<Area?> selectedArea = ValueNotifier(null);

  final GlobalKey<FormState> formKey = GlobalKey();

  final ValueNotifier<Phone?> phone = ValueNotifier(null);
  final ValueNotifier<Phone?> emergencyPhone = ValueNotifier(null);
  final ValueNotifier<AddressType> selectedType =
      ValueNotifier(AddressType.home);

  AddEditAddressViewModel._init();
  static AddEditAddressViewModel? _instance;
  static AddEditAddressViewModel get instance {
    _instance ??= AddEditAddressViewModel._init();
    return _instance!;
  }

  AddEditAddressViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryAddingAddress(BuildContext context) async {
    final valid = formKey.currentState?.validate();
  }
}

enum AddressType { home, office }
