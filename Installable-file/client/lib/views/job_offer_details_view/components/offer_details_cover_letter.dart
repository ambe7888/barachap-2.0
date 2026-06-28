import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

class OfferDetailsCoverLetter extends StatelessWidget {
  final String coverLetter;
  const OfferDetailsCoverLetter({super.key, required this.coverLetter});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            LocalKeys.coverLetter,
            style: context.titleLarge?.bold,
          ),
          12.toHeight,
          Text(
            coverLetter,
            style: context.titleSmall,
          ),
        ],
      ),
    );
  }
}
