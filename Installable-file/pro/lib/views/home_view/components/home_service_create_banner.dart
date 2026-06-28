import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/custom_button.dart';
import 'package:provider/provider.dart';

import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import '../../add_edit_service_view/add_edit_service_view.dart';

class HomeServiceCreateBanner extends StatelessWidget {
  const HomeServiceCreateBanner({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeService>(builder: (context, ts, child) {
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
            SvgPicture.asset(
              'assets/svg/finding.svg',
            ),
            16.toHeight,
            Text(
              LocalKeys.createService,
              style: context.titleLarge?.bold,
            ),
            4.toHeight,
            Text(
              LocalKeys.createYourOwnServiceAndStart,
              textAlign: TextAlign.center,
              style: context.bodyMedium?.copyWith(
                color: context.color.secondaryContrastColor,
              ),
            ),
            24.toHeight,
            CustomButton(
                onPressed: () {
                  AddEditServiceViewModel.dispose;
                  context.toPage(const AddEditServiceView());
                },
                btText: LocalKeys.createService,
                isLoading: false)
          ],
        ),
      );
    });
  }
}
