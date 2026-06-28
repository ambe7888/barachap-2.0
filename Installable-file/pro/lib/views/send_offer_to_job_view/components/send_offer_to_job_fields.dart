import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/job_services/job_details_service.dart';
import '../../../utils/components/currency_icon.dart';
import '../../../utils/components/field_with_label.dart';
import '../../../view_models/job_details_view_model/job_details_view_model.dart';
import '../../job_details_view/components/job_details_title_budget.dart';

class SendOfferToJobFields extends StatelessWidget {
  const SendOfferToJobFields({super.key});

  @override
  Widget build(BuildContext context) {
    final jdm = JobDetailsViewModel.instance;
    return Consumer<JobDetailsService>(builder: (context, jd, child) {
      final jobDetails = jd.jobDetailsModel.jobDetails!;
      return Form(
        key: jdm.formKey,
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              JobDetailsTitleBudget(
                title: jobDetails.title ?? "--",
                budget: jobDetails.budget,
                createdAt: DateTime.now(),
                category: jobDetails.category ?? "---",
                padding: EdgeInsets.zero,
              ),
              Divider(
                height: 32,
                thickness: 2,
                color: context.color.primaryBorderColor,
              ),
              16.toHeight,
              FieldWithLabel(
                label: LocalKeys.yourOffer,
                hintText: LocalKeys.enterYourOfferAmount,
                isRequired: true,
                controller: jdm.priceController,
                keyboardType: TextInputType.number,
                prefixIcon: const Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CurrencyIcon(
                      height: 32,
                    ),
                  ],
                ),
                validator: (value) {
                  if (value.toString().tryToParse <= 0) {
                    return LocalKeys.invalidPrice;
                  }
                  if (jobDetails.budget < value.toString().tryToParse) {
                    return LocalKeys.offerAmountShouldBeLessThenJobBudget;
                  }
                  return null;
                },
              ),
              FieldWithLabel(
                label: LocalKeys.coverLetter,
                hintText: LocalKeys.writeAboutTheServiceYourOffering,
                controller: jdm.coverLetterController,
                minLines: 3,
              ),
            ],
          ),
        ),
      );
    });
  }
}
