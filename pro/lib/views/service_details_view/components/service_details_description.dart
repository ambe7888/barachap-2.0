import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/services/service_services/service_details_service.dart';
import 'package:provider/provider.dart';
import 'package:readmore/readmore.dart';

import '../../../helper/local_keys.g.dart';

class ServiceDetailsDescription extends StatelessWidget {
  const ServiceDetailsDescription({super.key});

  @override
  Widget build(BuildContext context) {
    final desc = Provider.of<ServiceDetailsService>(context, listen: false)
        .serviceDetailsModel
        .allServices
        ?.description;
    return ReadMoreText(
      desc ?? "---",
      trimLines: 3,
      trimCollapsedText: LocalKeys.showMore,
      trimExpandedText: LocalKeys.showLess,
      style: context.bodySmall,
    );
  }
}
