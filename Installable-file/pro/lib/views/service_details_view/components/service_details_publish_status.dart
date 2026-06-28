import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../../../customizations/colors.dart';
import '../../../services/service_services/service_details_service.dart';
import '../../../utils/components/alerts.dart';

class ServiceDetailsPublishStatus extends StatelessWidget {
  final ServiceDetailsService sd;
  const ServiceDetailsPublishStatus({super.key, required this.sd});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      contentPadding: EdgeInsets.zero,
      minVerticalPadding: 0,
      title: Text(
        LocalKeys.servicePublished,
        style: context.titleMedium?.bold,
      ),
      trailing: Transform.scale(
        scale: .8,
        child: Switch(
          value: sd.serviceDetailsModel.allServices?.isPublished ?? false,
          onChanged: (newValue) {
            Alerts().confirmationAlert(
                context: context,
                title: LocalKeys.areYouSure,
                buttonText: LocalKeys.changeO,
                buttonColor: primaryColor,
                onConfirm: () async {
                  await sd.tryChangingPublishStatus().then((v) {
                    if (v != true) return;
                    context.pop;
                  });
                });
          },
        ),
      ),
    );
  }
}
