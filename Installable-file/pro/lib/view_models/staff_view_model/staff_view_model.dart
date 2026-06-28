import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/staff_models/staff_list_model.dart';
import 'package:prohand/services/staff_services/add_edit_staff_service.dart';
import 'package:prohand/services/staff_services/staff_list_service.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:provider/provider.dart';

class StaffViewModel {
  final TextEditingController firstNameController = TextEditingController();
  final TextEditingController lastNameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController aboutController = TextEditingController();

  final ValueNotifier<File?> selectedImage = ValueNotifier(null);
  final ValueNotifier<bool> isLoading = ValueNotifier(false);

  final GlobalKey<FormState> formKey = GlobalKey();

  String? imageUrl;
  dynamic staffId;

  void selectImage() async {
    FilePickerResult? files = await FilePicker.platform
        .pickFiles(
      allowMultiple: false,
      type: FileType.image,
    )
        .onError((e, _) {
      debugPrint(e.toString());
      return null;
    });
    if (files?.files.firstOrNull?.path != null) {
      selectedImage.value = File(files!.files.firstOrNull!.path!);
    }
  }

  StaffViewModel._init();
  static StaffViewModel? _instance;
  static StaffViewModel get instance {
    _instance ??= StaffViewModel._init();
    return _instance!;
  }

  StaffViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryAddEditStaff(BuildContext context) async {
    context.unFocus;
    if (!(formKey.currentState?.validate() ?? false)) return;
    isLoading.value = true;
    if (staffId == null) {
      await AddEditStaffService().tryAddingStaff(context).then((v) {
        if (v == true) {
          Provider.of<StaffListService>(context, listen: false)
              .fetchStaffList();
          context.pop;
        }
      });
    } else {
      await AddEditStaffService().tryEditingStaff(context).then((v) {
        if (v == true) {
          Provider.of<StaffListService>(context, listen: false)
              .fetchStaffList();
          context.pop;
        }
      });
    }
    isLoading.value = false;
  }

  void initStaff(Staff staff) {
    staffId = staff.id;
    firstNameController.text = staff.firstName ?? "";
    lastNameController.text = staff.lastName ?? "";
    emailController.text = staff.email ?? "";
    phoneController.text = staff.phone ?? "";
    imageUrl = staff.image ?? "";
  }

  void tryRemovingStaff(BuildContext context, id) {
    Alerts().confirmationAlert(
      context: context,
      title: LocalKeys.areYouSure,
      buttonText: LocalKeys.removeStaff,
      onConfirm: () async {
        await AddEditStaffService().tryRemovingStaff(context, id).then((v) {
          if (v == true) {
            Provider.of<StaffListService>(context, listen: false)
                .removeStaff(id);
            context.pop;
          }
        });
      },
    );
  }
}
