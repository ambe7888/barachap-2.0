import 'package:flutter/src/widgets/framework.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/view_models/job_list_view_model/job_list_view_model.dart';
import 'package:provider/provider.dart';

import '../../helper/constant_helper.dart';
import '../../helper/local_keys.g.dart';
import '../../services/jobs/job_details_service.dart';
import '../../utils/components/alerts.dart';

class JobDetailsViewModel {
  JobDetailsViewModel._init();
  static JobDetailsViewModel? _instance;
  static JobDetailsViewModel get instance {
    _instance ??= JobDetailsViewModel._init();
    return _instance!;
  }

  JobDetailsViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  void tryDeletingJob(BuildContext context) async {
    final jdProvider = Provider.of<JobDetailsService>(context, listen: false);
    Alerts().confirmationAlert(
      context: context,
      title: LocalKeys.deleteJobConfirmation,
      onConfirm: () async {
        await jdProvider.tryDeletingJob().then((r) {
          if (r == true) {
            JobListViewModel.instance.refreshKey.currentState?.show();
            context.pop;
          }
          context.pop;
        });
      },
      buttonText: LocalKeys.delete,
      buttonColor: color.primaryWarningColor,
      description: LocalKeys.deleteJobDescription,
    );
  }
}
