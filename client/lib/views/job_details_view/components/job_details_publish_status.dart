import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/services/jobs/job_details_service.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:provider/provider.dart';

import '../../../helper/local_keys.g.dart';

class JobDetailsPublishStatus extends StatelessWidget {
  final bool publishStatus;
  const JobDetailsPublishStatus({super.key, required this.publishStatus});

  @override
  Widget build(BuildContext context) {
    final jdProvider = Provider.of<JobDetailsService>(context, listen: false);
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(
          color: context.color.primaryBorderColor.withOpacity(0.6),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: ListTile(
        contentPadding: EdgeInsets.zero,
        minVerticalPadding: 0,
        title: Text(
          publishStatus ? "En ligne" : "Hors ligne",
          style: context.titleMedium?.bold,
        ),
        trailing: Transform.scale(
          scale: .8,
          child: Switch(
            value: publishStatus,
            onChanged:
                (jdProvider.jobDetailsModel.isJobHired?.toString() == "0")
                    ? (newValue) {
                        Alerts().confirmationAlert(
                            context: context,
                            title: LocalKeys.areYouSure,
                            buttonText: LocalKeys.changeO,
                            buttonColor: primaryColor,
                            onConfirm: () async {
                              await jdProvider
                                  .tryChangingJobPublishStatus()
                                  .then((v) {
                                if (v != true) return;
                                context.pop;
                              });
                            });
                      }
                    : null,
          ),
        ),
        onTap: (jdProvider.jobDetailsModel.isJobHired?.toString() == "0")
            ? () {
                Alerts().confirmationAlert(
                    context: context,
                    title: LocalKeys.areYouSure,
                    buttonText: LocalKeys.changeO,
                    buttonColor: primaryColor,
                    onConfirm: () async {
                      await jdProvider.tryChangingJobPublishStatus().then((v) {
                        if (v != true) return;
                        context.pop;
                      });
                    });
              }
            : null,
      ),
    );
  }
}
