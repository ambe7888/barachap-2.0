import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/view_models/job_details_view_model/job_details_view_model.dart';
import 'package:prohand/views/send_offer_to_job_view/send_offer_to_job_view.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../services/job_services/job_details_service.dart';

class JobDetailsButtons extends StatelessWidget {
  const JobDetailsButtons({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: Consumer<JobDetailsService>(builder: (context, jd, child) {
        return ElevatedButton(
          onPressed: jd.jobDetailsModel.jobOffer != null
              ? null
              : () {
                  JobDetailsViewModel.dispose;
                  context.toPage(const SendOfferToJobView());
                },
          child: Text(LocalKeys.sendAnOffer),
        );
      }),
    );
  }
}
