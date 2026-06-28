import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/models/category_model.dart';

class PostJobViewModel {
  TextEditingController titleController = TextEditingController();
  TextEditingController budgetController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  TextEditingController addressController = TextEditingController();

  PageController pageController = PageController(initialPage: 0);
  ValueNotifier<bool> isLoading = ValueNotifier(false);
  ValueNotifier<int> pageIndex = ValueNotifier(0);
  ValueNotifier<DateTime?> selectedDate = ValueNotifier(null);
  ValueNotifier<TimeOfDay?> selectedTime = ValueNotifier(null);
  ValueNotifier<Category?> selectedCategory =
      ValueNotifier(Category(name: "Painting"));

  ValueNotifier<List<File>> selectedGallery = ValueNotifier([]);

  PostJobViewModel._init();
  static PostJobViewModel? _instance;
  static PostJobViewModel get instance {
    _instance ??= PostJobViewModel._init();
    return _instance!;
  }

  PostJobViewModel._dispose();
  static bool get dispose {
    for (var file in _instance?.selectedGallery.value ?? []) {
      file.remove();
    }

    return true;
  }

  void selectGalleryImage() async {
    FilePickerResult? files = await FilePicker.platform
        .pickFiles(
      allowMultiple: true,
      type: FileType.image,
    )
        .onError((e, _) {
      debugPrint(e.toString());
      return null;
    });
    Set<File> set = Set.from(selectedGallery
        .value); // Create a set from the first list to avoid duplicates
    set.addAll(files?.files.map((f) {
          return File(f.path!);
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
        break;
      case 1:
        break;
      case 2:
        return;

      default:
        debugPrint("default".toString());
    }
    await pageController.nextPage(
        duration: 300.milliseconds, curve: Curves.easeIn);
    pageIndex.value = pageController.page?.toInt() ?? 0;
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
}
