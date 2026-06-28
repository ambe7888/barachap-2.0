import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/models/address_models/address_model.dart';
import 'package:prohandy_client/view_models/post_job_view_model/post_job_view_model.dart';
import 'package:prohandy_client/views/post_job_view/components/job_preview_buttons.dart';

import '../../helper/local_keys.g.dart';
import '../../utils/components/navigation_pop_icon.dart';
import '../job_details_view/components/job_details_address.dart';
import '../job_details_view/components/job_details_date_schedule.dart';
import '../job_details_view/components/job_details_description.dart';
import '../job_details_view/components/job_details_gellary.dart';
import '../job_details_view/components/job_details_title_budget.dart';

class PostJobPreview extends StatelessWidget {
  static const routeName = "post_job_preview";
  const PostJobPreview({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.summary),
      ),
      body: SingleChildScrollView(
        padding: 16.paddingV,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            JobDetailsTitleBudget(
              title: pjm.titleController.text,
              budget: pjm.budgetController.text.tryToParse,
              jobOffersCount: 0,
              publishStatus: null,
            ),
            8.toHeight,
            JobDetailsDateSchedule(
                date: pjm.selectedDate.value ?? DateTime.now(),
                schedule: (pjm.selectedTime.value ?? TimeOfDay.now())
                    .format(context)),
            8.toHeight,
            JobDetailsAddress(
                address: Address(
              title: "title",
              address: pjm.selectedAddress.value?.address ?? "",
              phone: "phone",
            )),
            8.toHeight,
            JobDetailsDescription(description: pjm.descriptionController.text),
            8.toHeight,
            if (pjm.selectedGallery.value.isNotEmpty)
              JobDetailsGallery(
                gallery:
                    pjm.selectedGallery.value.map((file) => file.path).toList(),
                isFromPreview: true,
              ),
          ],
        ),
      ),
      bottomNavigationBar: const JobPreviewButtons(),
    );
  }
}
