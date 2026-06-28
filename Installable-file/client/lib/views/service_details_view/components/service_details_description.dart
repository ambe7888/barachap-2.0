import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../models/service/service_details_model.dart';

class ServiceDetailsDescription extends StatelessWidget {
  final ServiceDetailsModel serviceDetails;

  const ServiceDetailsDescription({super.key, required this.serviceDetails});

  @override
  Widget build(BuildContext context) {
    return ReadMoreText(
      serviceDetails.allServices?.description ?? "---",
      trimMode: TrimMode.Line,
      trimLines: 3,
      colorClickableText: primaryColor,
      trimCollapsedText: LocalKeys.showMore,
      trimExpandedText: " ${LocalKeys.showLess}",
      style: context.bodyMedium,
    );
  }
}
