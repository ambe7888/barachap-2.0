import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/string_extension.dart';

import '../../helper/local_keys.g.dart';

class IdentityVerificationFormViewModel {
  ValueNotifier<File?> frontImage = ValueNotifier(null);
  ValueNotifier<File?> backImage = ValueNotifier(null);

  IdentityVerificationFormViewModel._init();
  static IdentityVerificationFormViewModel? _instance;
  static IdentityVerificationFormViewModel get instance {
    _instance ??= IdentityVerificationFormViewModel._init();
    return _instance!;
  }

  IdentityVerificationFormViewModel._dispose();
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
      frontImage.value = File(file!.files.first.path!);
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
      backImage.value = File(file!.files.first.path!);
      LocalKeys.fileSelected.showToast();
    } catch (error) {
      LocalKeys.fileSelectFailed.showToast();
    }
  }
}
