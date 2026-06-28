import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/services/job_services/job_details_service.dart';
import 'package:provider/provider.dart';

import '../../helper/local_keys.g.dart';
import '../../helper/svg_assets.dart';
import '../../utils/components/alerts.dart';

class JobDetailsViewModel {
  final TextEditingController priceController = TextEditingController();
  final TextEditingController coverLetterController = TextEditingController();

  final ValueNotifier<bool> offerSendingLoading = ValueNotifier(false);
  final GlobalKey<RefreshIndicatorState> refreshKey =
      GlobalKey<RefreshIndicatorState>();

  final GlobalKey<FormState> formKey = GlobalKey();

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

  trySendingOffer(BuildContext context, id) async {
    final isValid = formKey.currentState?.validate();

    if (isValid != true) return;

    offerSendingLoading.value = true;
    await Provider.of<JobDetailsService>(context, listen: false)
        .trySendingOffer(id)
        .then((value) async {
      offerSendingLoading.value = false;
      if (value != true) return;
      await Alerts().showInfoDialogue(
        context: context,
        title: LocalKeys.offerSent,
        infoAsset: SvgAssets.addFilled
            .toSVGSized(80, color: context.color.primarySuccessColor),
        description: LocalKeys.offerSentSuccessfully,
      );
      refreshKey.currentState?.show();
      context.pop;
    });
  }
}
