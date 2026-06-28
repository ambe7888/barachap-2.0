import 'dart:io';

import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:path_provider/path_provider.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/file_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/models/category_model.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:provider/provider.dart';

import '../../views/post_job_view/post_job_preview.dart';

class PostJobViewModel {
  TextEditingController titleController = TextEditingController();
  TextEditingController budgetController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  TextEditingController addressController = TextEditingController();

  PageController pageController = PageController(initialPage: 0);
  ValueNotifier<bool> isLoading = ValueNotifier(false);
  ValueNotifier<bool> imageLoading = ValueNotifier(false);
  ValueNotifier<int> pageIndex = ValueNotifier(0);
  ValueNotifier<DateTime?> selectedDate = ValueNotifier(null);
  ValueNotifier<TimeOfDay?> selectedTime = ValueNotifier(null);
  ValueNotifier<Address?> selectedAddress = ValueNotifier(null);
  ValueNotifier<Category?> selectedCategory = ValueNotifier(null);

  ValueNotifier<List<File>> selectedGallery = ValueNotifier([]);

  final GlobalKey<FormState> basicFormKey = GlobalKey(debugLabel: "basic");
  final GlobalKey<FormState> budgetFormKey = GlobalKey();
  final ImagePicker _picker = ImagePicker();

  dynamic jobId;

  PostJobViewModel._init();
  static PostJobViewModel? _instance;
  static PostJobViewModel get instance {
    _instance ??= PostJobViewModel._init();
    return _instance!;
  }

  PostJobViewModel._dispose();
  static bool get dispose {
    for (File file in _instance?.selectedGallery.value ?? []) {
      file.remove();
    }
    _instance = null;
    return true;
  }

  bool get basicInfoValidation {
    if (basicFormKey.currentState?.validate() != true) return false;
    final validations = {
      selectedTime.value: LocalKeys.selectTime,
      selectedDate.value: LocalKeys.selectDate,
      selectedAddress.value: LocalKeys.selectAddress,
      selectedCategory.value: LocalKeys.selectCategory,
    };
    bool valid = true;
    for (var entry in validations.entries) {
      debugPrint(entry.value.toString());
      if (entry.key == null) {
        entry.value.showToast();
        debugPrint(entry.value.toString());
        valid = false;
        break;
      }
    }

    return valid;
  }

  void selectGalleryImage() async {
    final files = await _picker.pickMultiImage().onError(
      (error, stackTrace) {
        error.toString().showToast();
        return [];
      },
    );
    Set<File> set = Set.from(selectedGallery
        .value); // Create a set from the first list to avoid duplicates
    set.addAll(files.map((f) {
          return File(f.path);
        }) ??
        []);
    selectedGallery.value = set.toList();
  }

  void removeFromGallery(File image) async {
    Set<File> set = Set.from(selectedGallery
        .value); // Create a set from the first list to avoid duplicates
    set.remove(image);

    selectedGallery.value = set.toList();
    try {
      if (await image.exists()) {
        await image.delete();
      }
    } catch (e) {
      debugPrint('Error deleting file from cache: $e');
    }
  }

  continueForward(BuildContext context) async {
    debugPrint(pageIndex.value.toString());
    switch (pageIndex.value) {
      case 0:
        if (basicInfoValidation != true) return;
        await pageController.nextPage(
            duration: 300.milliseconds, curve: Curves.easeIn);
        pageIndex.value = pageController.page?.toInt() ?? 0;
        break;
      case 1:
        if (budgetFormKey.currentState?.validate() != true) return;
        context.animateToPage(const PostJobPreview());
        return;

      default:
        debugPrint("default".toString());
    }
  }

  goBack(BuildContext context) async {
    await pageController.previousPage(
        duration: 300.milliseconds, curve: Curves.easeIn);
    pageIndex.value = pageController.page?.toInt() ?? 0;
  }

  void resetPage(int index) {
    pageController.jumpToPage(index);
    pageIndex.value = index;
  }

  initJobEdit(BuildContext context) async {
    final jobDetails = Provider.of<JobDetailsService>(context, listen: false)
        .jobDetailsModel
        .jobDetails;
    if (jobDetails == null) return;

    jobId = jobDetails.id;
    titleController.text = jobDetails.title ?? "";
    descriptionController.text = jobDetails.description ?? "";
    budgetController.text = jobDetails.budget.toString();
    selectedAddress.value = jobDetails.jobLocation;
    selectedCategory.value = Category(id: jobDetails.categoryId);
    selectedDate.value = jobDetails.date;
    DateTime today = DateTime.now();
    final fSchedule = jobDetails.time?.trim();

    final formattedDate =
        "${DateFormat('yyyy-MM-dd').format(today)} $fSchedule";
    final tempDate = fSchedule == null
        ? null
        : DateFormat('yyyy-MM-dd hh:mm').tryParse(formattedDate);
    selectedTime.value =
        tempDate != null ? TimeOfDay.fromDateTime(tempDate) : null;
    fetchAndSetGalleryImages(jobDetails.galleryImages);
  }

  void fetchAndSetGalleryImages(List<String> images) async {
    List<File> downloadedFiles = [];
    imageLoading.value = true;
    for (String url in images) {
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final directory = await getTemporaryDirectory();
        final filePath = '${directory.path}/${url.split('/').last}';
        final file = File(filePath);
        await file.writeAsBytes(response.bodyBytes);
        downloadedFiles.add(file);
      }
    }
    imageLoading.value = false;
    selectedGallery.value = downloadedFiles;
  }
}
