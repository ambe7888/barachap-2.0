import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/category_dropdown.dart';
import 'package:prohand/utils/components/field_with_label.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';

import '../service_price_view/components/service_photos.dart';

class ServiceOverview extends StatelessWidget {
  const ServiceOverview({super.key});

  @override
  Widget build(BuildContext context) {
    final aes = AddEditServiceViewModel.instance;
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Form(
        key: aes.overviewFormKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Divider(
              height: 8,
              thickness: 8,
              color: context.color.backgroundColor,
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    LocalKeys.overview,
                    style: context.titleLarge?.bold,
                  ),
                  Text(
                    LocalKeys.addedServiceOverview,
                    style: context.bodySmall,
                  ),
                  24.toHeight,
                  FieldWithLabel(
                    label: LocalKeys.title,
                    hintText: LocalKeys.enterTitle,
                    controller: aes.titleController,
                    isRequired: true,
                    validator: (value) {
                      if (value.toString().trim().length < 10) {
                        return LocalKeys.titleMustContainMoreCharacter;
                      }
                      return null;
                    },
                  ),
                  CategoryDropdown(
                    categoryNotifier: aes.categoryNotifier,
                    isRequired: true,
                  ),
                  FieldWithLabel(
                    label: LocalKeys.serviceUnit,
                    hintText: LocalKeys.serviceUnitExample,
                    controller: aes.unitController,
                  ),
                  FieldWithLabel(
                    label: LocalKeys.videoUrl,
                    hintText: LocalKeys.enterYoutubeVideoUrl,
                    controller: aes.videoUrlController,
                  ),
                  FieldWithLabel(
                    label: LocalKeys.description,
                    hintText: LocalKeys.enterDescription,
                    minLines: 3,
                    maxLines: 3,
                    controller: aes.descriptionController,
                    isRequired: true,
                    validator: (value) => value.toString().trim().length < 150
                        ? LocalKeys.serviceDescriptionLength
                        : null,
                  ),
                  const ServicePhotos()
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
