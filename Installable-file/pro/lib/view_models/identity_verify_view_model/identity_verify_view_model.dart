import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:prohand/app_static_values.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/profile_services/iv_manage_service.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';

class IVViewModel {
  final ValueNotifier<String> selectedIVType =
      ValueNotifier(idVTypes.keys.first);

  final TextEditingController countryController = TextEditingController();
  final TextEditingController stateController = TextEditingController();
  final TextEditingController cityController = TextEditingController();
  final TextEditingController idController = TextEditingController();
  final TextEditingController zipController = TextEditingController();
  final TextEditingController addressController = TextEditingController();

  final ValueNotifier<File?> frontImage = ValueNotifier(null);
  final ValueNotifier<File?> backImage = ValueNotifier(null);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  final GlobalKey<FormState> formKey = GlobalKey();

  IVViewModel._init();
  static IVViewModel? _instance;
  static IVViewModel get instance {
    _instance ??= IVViewModel._init();
    return _instance!;
  }

  IVViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void setFrontImage() async {
    try {
      FilePickerResult? file = await FilePicker.platform.pickFiles();
      if (file?.files.firstOrNull?.path == null) {
        return;
      }
      final File imageFile = File(file!.paths.first!);
      num maxSize = 1024 * 1024 * (profileImageMaxSize ?? 1); // 1MB
      final num fileSize = await imageFile.length();

      if (fileSize > maxSize) {
        LocalKeys.fileSizeExceeded.showToast();
        return;
      }
      frontImage.value = File(file.files.first.path!);
      LocalKeys.fileSelected.showToast();
    } catch (error) {
      LocalKeys.fileSelectFailed.showToast();
    }
  }

  void setBackImage() async {
    try {
      FilePickerResult? file = await FilePicker.platform.pickFiles();
      if (file?.files.firstOrNull?.path == null) {
        return;
      }
      final File imageFile = File(file!.paths.first!);
      num maxSize = 1024 * 1024 * (profileImageMaxSize ?? 1); // 1MB
      final num fileSize = await imageFile.length();

      if (fileSize > maxSize) {
        LocalKeys.fileSizeExceeded.showToast();
        return;
      }
      backImage.value = File(file.files.first.path!);
      LocalKeys.fileSelected.showToast();
    } catch (error) {
      LocalKeys.fileSelectFailed.showToast();
    }
  }

  void submitForIV(BuildContext context) async {
    isLoading.value = true;
    await Provider.of<IvManageService>(context, listen: false)
        .trySubmitForIV()
        .then((v) {
      if (v != true) return;
      context.pop;
    });
    isLoading.value = false;
  }
}
