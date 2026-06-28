import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../data/network/network_api_services.dart';
import '../../view_models/post_job_view_model/post_job_view_model.dart';

class PostJobService with ChangeNotifier {
  tryPostingJob() async {
    var url = AppUrls.postJobUrl;
    http.MultipartRequest request = http.MultipartRequest(
      "POST",
      Uri.parse(url),
    );

    final pjm = PostJobViewModel.instance;
    final now = DateTime.now();
    request.fields.addAll({
      'category_id': pjm.selectedCategory.value?.id?.toString() ?? "",
      'sub_category_id': '',
      'child_category_id': '',
      'state_id': pjm.selectedAddress.value?.stateId?.toString() ?? "",
      'city_id': pjm.selectedAddress.value?.cityId?.toString() ?? "",
      'area_id': pjm.selectedAddress.value?.areaId?.toString() ?? "",
      'title': pjm.titleController.text,
      'slug':
          pjm.titleController.text.replaceAll(" ", "-") +
          now.millisecondsSinceEpoch.toString(),
      'description': pjm.descriptionController.text,
      'budget': pjm.budgetController.text.tryToParse.toStringAsFixed(2),
      'date': pjm.selectedDate.value?.toIso8601String() ?? "",
      'phone': pjm.selectedAddress.value?.phone ?? "",
      'emergency_phone': pjm.selectedAddress.value?.emergencyPhone ?? "",
      'post_code': pjm.selectedAddress.value?.postCode ?? "",
      'time': DateFormat("HH:mm:ss", dProvider.languageSlug).format(
        DateTime(
          now.year,
          now.month,
          now.day,
          pjm.selectedTime.value?.hour ?? 00,
          pjm.selectedTime.value?.minute ?? 00,
        ),
      ),
      'address': pjm.selectedAddress.value?.address ?? "",
      'latitude': pjm.selectedAddress.value?.latitude?.toStringAsFixed(5) ?? "",
      'longitude':
          pjm.selectedAddress.value?.longitude?.toStringAsFixed(5) ?? "",
    });
    for (var i = 0; i < pjm.selectedGallery.value.length; i++) {
      final image = pjm.selectedGallery.value[i];
      request.files.add(
        await http.MultipartFile.fromPath('gallery_images[$i]', image.path),
      );
    }
    request.headers.addAll(acceptJsonAuthHeader);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.postJob,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      return true;
    }
  }

  tryEditingJob(jobId) async {
    var url = "${AppUrls.editJobUrl}/$jobId";
    http.MultipartRequest request = http.MultipartRequest(
      "POST",
      Uri.parse(url),
    );

    final pjm = PostJobViewModel.instance;
    final now = DateTime.now();
    request.fields.addAll({
      'category_id': pjm.selectedCategory.value?.id?.toString() ?? "",
      'sub_category_id': '',
      'child_category_id': '',
      'state_id': pjm.selectedAddress.value?.stateId?.toString() ?? "",
      'city_id': pjm.selectedAddress.value?.cityId?.toString() ?? "",
      'area_id': pjm.selectedAddress.value?.areaId?.toString() ?? "",
      'title': pjm.titleController.text,
      'slug':
          pjm.titleController.text.replaceAll(" ", "-") +
          now.millisecondsSinceEpoch.toString(),
      'description': pjm.descriptionController.text,
      'budget': pjm.budgetController.text,
      'date': pjm.selectedDate.value?.toIso8601String() ?? "",
      'time': DateFormat("HH:mm:ss").format(
        DateTime(
          now.year,
          now.month,
          now.day,
          pjm.selectedTime.value?.hour ?? 00,
          pjm.selectedTime.value?.minute ?? 00,
        ),
      ),
      'address': pjm.selectedAddress.value?.address ?? "",
      'latitude': pjm.selectedAddress.value?.latitude?.toStringAsFixed(5) ?? "",
      'longitude':
          pjm.selectedAddress.value?.longitude?.toStringAsFixed(5) ?? "",
    });
    for (var i = 0; i < pjm.selectedGallery.value.length; i++) {
      final image = pjm.selectedGallery.value[i];
      request.files.add(
        await http.MultipartFile.fromPath('gallery_images[$i]', image.path),
      );
    }
    request.headers.addAll(acceptJsonAuthHeader);
    final responseData = await NetworkApiServices().postWithFileApi(
      request,
      LocalKeys.postJob,
      headers: acceptJsonAuthHeader,
    );

    if (responseData != null) {
      return true;
    }
  }
}
