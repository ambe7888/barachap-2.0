import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/image_assets.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';

import '../../../view_models/post_job_view_model/post_job_view_model.dart';
import '../../post_job_view/post_job_view.dart';

class HomeJobPostBanner extends StatelessWidget {
  const HomeJobPostBanner({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: context.width,
      padding: const EdgeInsets.symmetric(
        horizontal: 24,
        vertical: 16,
      ),
      decoration: BoxDecoration(
        color: context.color.accentContrastColor,
      ),
      child: Column(
        children: [
          ImageAssets.finding.toAImage(),
          16.toHeight,
          Text(
            LocalKeys.postAJob,
            style: context.titleLarge?.bold,
          ),
          4.toHeight,
          Text(
            LocalKeys.didNotFindWhatLookingFor,
            style: context.bodyMedium?.copyWith(
              color: context.color.secondaryContrastColor,
            ),
          ),
          24.toHeight,
          CustomButton(
              onPressed: () {
                PostJobViewModel.dispose;
                context.toPage(PostJobView());
              },
              btText: LocalKeys.postAJob,
              isLoading: false)
        ],
      ),
    );
  }
}
