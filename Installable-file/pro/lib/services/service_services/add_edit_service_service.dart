import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:prohand/data/network/network_api_services.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

class AddEditServiceService with ChangeNotifier {
  List<Map<String, String>> offerings = [];
  List<Map<String, String>> excludes = [];
  List faq = [];

  refresh() {
    notifyListeners();
  }

  tryCreatingService() async {
    final aem = AddEditServiceViewModel.instance;
    var request =
        http.MultipartRequest('POST', Uri.parse(AppUrls.createServiceUrl));
    final data = {
      'category_id': aem.categoryNotifier.value?.id?.toString() ?? "1",
      'title': aem.titleController.text,
      'slug': aem.titleController.text +
          DateTime.now().microsecondsSinceEpoch.toString(),
      'price': aem.priceController.text,
      'discount_price': aem.discountPriceController.text,
      'video_url': aem.videoUrlController.text,
      'description': aem.descriptionController.text,
      'all_include_service': jsonEncode(aem.includes.value),
      'all_excludes_service': jsonEncode(aem.excludes.value),
      'all_faqs_service': jsonEncode(aem.faq.value),
      'all_addons_service': jsonEncode(aem.addons.value),
      'unit': aem.unitController.text,
      'staffs_id': aem.anyStaffOption.value
          ? ""
          : aem.staffs.value
              .map((id) => id.toString().tryToParse)
              .toList()
              .join(","),
      'disable_staff': aem.selectStaffOption.value ? "1" : "0",
    };
    request.fields.addAll(data);
    int index = 0;
    for (var image in aem.selectedGallery.value) {
      final galleryImage =
          await http.MultipartFile.fromPath('gallery_images[]', image.path);
      debugPrint(index.toString());
      if (index == 0) {
        final primaryImage =
            await http.MultipartFile.fromPath('image', image.path);
        request.files.add(primaryImage);
      }
      request.files.add(galleryImage);
      index++;
    }

    request.headers.addAll(acceptJsonAuthHeader);

    final responseBody = await NetworkApiServices().postWithFileApi(
        request, LocalKeys.createService,
        headers: acceptJsonAuthHeader);

    if (responseBody != null) {
      return true;
    } else {}
  }

  tryEditingService(id) async {
    final aem = AddEditServiceViewModel.instance;
    var request = http.MultipartRequest(
        'POST', Uri.parse("${AppUrls.editServiceUrl}/$id"));

    if (AppUrls.deleteAccountUrl.toLowerCase().contains("xgenious.com")) {
      await Future.delayed(const Duration(seconds: 2));
      "This feature is turned off for demo app".showToast();
      return;
    }
    final data = {
      'category_id': aem.categoryNotifier.value?.id?.toString() ?? "1",
      'title': aem.titleController.text,
      'slug': aem.titleController.text +
          DateTime.now().microsecondsSinceEpoch.toString(),
      'price': aem.priceController.text,
      'discount_price': aem.discountPriceController.text,
      'video_url': aem.videoUrlController.text,
      'description': aem.descriptionController.text,
      'all_include_service': jsonEncode(aem.includes.value),
      'all_excludes_service': jsonEncode(aem.excludes.value),
      'all_faqs_service': jsonEncode(aem.faq.value),
      'all_addons_service': jsonEncode(aem.addons.value),
      'unit': aem.unitController.text,
      'staffs_id': aem.anyStaffOption.value
          ? ""
          : aem.staffs.value
              .map((id) => id.toString().tryToParse)
              .toList()
              .join(","),
      'disable_staff': aem.selectStaffOption.value ? "1" : "0",
    };
    request.fields.addAll(data);
    int index = 0;
    for (var image in aem.selectedGallery.value) {
      final galleryImage =
          await http.MultipartFile.fromPath('gallery_images[]', image.path);
      if (index == 0) {
        final primaryImage =
            await http.MultipartFile.fromPath('image', image.path);

        request.files.add(primaryImage);
      }
      request.files.add(galleryImage);
      index++;
    }

    request.headers.addAll(acceptJsonAuthHeader);

    final responseBody = await NetworkApiServices().postWithFileApi(
        request, LocalKeys.editService,
        headers: acceptJsonAuthHeader);

    if (responseBody != null) {
      return true;
    } else {}
  }
}
