import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';
import 'package:prohandy_client/view_models/service_provider_view_model/service_provider_view_model.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';
import '../../../models/provider_details_model.dart';

class ServiceProviderAbout extends StatelessWidget {
  final UserDetails userDetails;
  const ServiceProviderAbout({super.key, required this.userDetails});

  @override
  Widget build(BuildContext context) {
    final spm = ServiceProviderViewModel.instance;
    return Column(
      children: [
        Container(
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              InfoTile(
                  title: LocalKeys.jobCompleted,
                  value:
                      "${userDetails.totalServiceOrderCompleted + userDetails.totalJobOrderCompleted}"),
              12.toHeight,
              InfoTile(
                  title: LocalKeys.memberSince,
                  value: DateFormat(
                    "MMM dd, yyyy",
                    dProvider.languageSlug,
                  ).format(userDetails.createdAt ?? DateTime.now())),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              InfoTile(
                  title: LocalKeys.serviceArea,
                  value: userDetails.providerServiceArea?.address ?? "---"),
              12.toHeight,
              InfoTile(
                  title: LocalKeys.totalStaff,
                  value: "${userDetails.providerStaffs?.length ?? 0}"),
              Divider(
                color: context.color.primaryBorderColor,
                height: 32,
              ),
              ReadMoreText(
                "Your information, including Personal Data, is processed at the Company's operating offices and in any other places where the parties involved in the processing are located. It means that this information may be transferred to — and maintained on — computers located outside of Your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from Your jurisdiction.",
                trimMode: TrimMode.Line,
                trimLines: 3,
                colorClickableText: primaryColor,
                trimCollapsedText: LocalKeys.showMore,
                trimExpandedText: " ${LocalKeys.showLess}",
                style: context.bodyMedium,
              )
            ],
          ),
        ),
        8.toHeight,
      ],
    );
  }
}
