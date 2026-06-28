import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/provider_model.dart';
import 'package:prohandy_client/utils/components/info_tile.dart';

import '../../../helper/constant_helper.dart';

class OfferDetailsProviderSubInfo extends StatelessWidget {
  final ProviderModel provider;
  const OfferDetailsProviderSubInfo({super.key, required this.provider});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          InfoTile(
              title: LocalKeys.jobCompleted,
              value: provider.jobCompleted.toString()),
          12.toHeight,
          InfoTile(
              title: LocalKeys.memberSince,
              value: DateFormat("MMM dd, YYYY", dProvider.languageSlug)
                  .format(provider.createdAt!)),
          Divider(
            color: context.color.primaryBorderColor,
            height: 32,
          ),
          InfoTile(
              title: LocalKeys.address,
              value: provider.address?.address ??
                  (provider.address?.stateId != null
                      ? provider.address!.stateId.toString()
                      : "---")),
          12.toHeight,
          InfoTile(
              title: LocalKeys.verificationStatus,
              value: provider.isVerified
                  ? LocalKeys.verified
                  : LocalKeys.notVerified),
        ],
      ),
    );
  }
}
