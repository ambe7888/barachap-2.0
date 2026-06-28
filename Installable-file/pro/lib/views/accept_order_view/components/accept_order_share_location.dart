import 'dart:math';

import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/models/address_models/address_model.dart';

class AcceptOrderShareLocation extends StatelessWidget {
  const AcceptOrderShareLocation({super.key, Address? address});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      color: context.color.accentContrastColor,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          CircleAvatar(
            radius: 26,
            backgroundColor: context.color.mutedSuccessColor,
            child: SvgAssets.mapPin
                .toSVGSized(24, color: context.color.primarySuccessColor),
          ),
          12.toWidth,
          Expanded(
              flex: 1,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    LocalKeys.location,
                    style: context.titleMedium?.bold,
                  ),
                  4.toHeight,
                  Text(
                    LocalKeys.shareLocationWithAssignedStaffs,
                    style: context.bodySmall,
                  ),
                ],
              )),
          12.toWidth,
          Transform.rotate(
            angle: context.dProvider.textDirectionRight ? pi : 0,
            child: SvgAssets.chevron.toSVGSized(
              20,
              color: context.color.secondaryContrastColor,
            ),
          ),
        ],
      ),
    );
  }
}
