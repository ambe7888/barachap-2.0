import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/job_services/job_details_service.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/job_details_view_model/job_details_view_model.dart';

class SendOfferToJobButton extends StatelessWidget {
  const SendOfferToJobButton({super.key});

  @override
  Widget build(BuildContext context) {
    final jdm = JobDetailsViewModel.instance;
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: ValueListenableBuilder(
          valueListenable: jdm.offerSendingLoading,
          builder: (context, loading, child) {
            return CustomButton(
              onPressed: () {
                jdm.trySendingOffer(
                    context,
                    Provider.of<JobDetailsService>(context, listen: false)
                        .jobDetailsModel
                        .jobDetails
                        ?.id);
              },
              btText: LocalKeys.sendOffer,
              isLoading: loading,
            );
          }),
    );
  }
}
