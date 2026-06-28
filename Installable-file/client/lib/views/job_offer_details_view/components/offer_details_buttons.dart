import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/jobs/offer_details_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/view_models/application_details_view_model/application_details_view_model.dart';
import 'package:prohandy_client/views/job_hire_summary_view/job_hire_summary_view.dart';
import 'package:provider/provider.dart';

class OfferDetailsButtons extends StatelessWidget {
  const OfferDetailsButtons({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      decoration: BoxDecoration(
          color: context.color.accentContrastColor,
          border:
              Border(top: BorderSide(color: context.color.primaryBorderColor))),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
              flex: 1,
              child: OutlinedButton(
                  onPressed: () {
                    Alerts().confirmationAlert(
                        context: context,
                        title: LocalKeys.areYouSure,
                        onConfirm: () async {
                          await Provider.of<OfferDetailsService>(context,
                                  listen: false)
                              .tryRejectOffer();
                          context.pop;
                        },
                        buttonColor: context.color.primaryWarningColor,
                        buttonText: LocalKeys.reject);
                  },
                  child: Text(LocalKeys.reject))),
          12.toWidth,
          Expanded(
              flex: 1,
              child: ElevatedButton(
                  onPressed: () {
                    OfferDetailsViewModel.dispose;
                    context.toNamed(JobHireSummaryView.routeName);
                  },
                  child: Text(LocalKeys.hireNow))),
        ],
      ),
    );
  }
}
