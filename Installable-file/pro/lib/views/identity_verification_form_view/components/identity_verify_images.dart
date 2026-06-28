import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_network_image.dart';
import 'package:prohand/utils/components/custom_squircle_widget.dart';

import '../../../view_models/identity_verify_view_model/identity_verify_view_model.dart';

class IdentityVerifyImages extends StatelessWidget {
  const IdentityVerifyImages({super.key});

  @override
  Widget build(BuildContext context) {
    final ivm = IVViewModel.instance;
    return Row(
      children: [
        ValueListenableBuilder(
            valueListenable: ivm.frontImage,
            builder: (context, value, child) {
              return Expanded(
                flex: 1,
                child: GestureDetector(
                  onTap: () {
                    ivm.setFrontImage();
                  },
                  child: SquircleContainer(
                    height: 100,
                    color: mutedPrimaryColor,
                    radius: 12,
                    child: value != null
                        ? CustomNetworkImage(
                            fit: BoxFit.cover,
                            filePath: value.path,
                          )
                        : Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              const Icon(
                                Icons.add_circle_outline_rounded,
                                color: primaryColor,
                              ),
                              4.toHeight,
                              Text(
                                LocalKeys.uploadIdFront,
                                style: context.titleSmall?.bold
                                    .copyWith(color: primaryColor),
                              )
                            ],
                          ),
                  ),
                ),
              );
            }),
        12.toWidth,
        ValueListenableBuilder(
            valueListenable: ivm.backImage,
            builder: (context, value, child) {
              return Expanded(
                  flex: 1,
                  child: GestureDetector(
                    onTap: () {
                      ivm.setBackImage();
                    },
                    child: SquircleContainer(
                      height: 100,
                      color: mutedPrimaryColor,
                      radius: 12,
                      child: value != null
                          ? CustomNetworkImage(
                              fit: BoxFit.cover,
                              filePath: value.path,
                            )
                          : Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                const Icon(
                                  Icons.add_circle_outline_rounded,
                                  color: primaryColor,
                                ),
                                4.toHeight,
                                Text(
                                  LocalKeys.uploadIdBack,
                                  style: context.titleSmall?.bold
                                      .copyWith(color: primaryColor),
                                )
                              ],
                            ),
                    ),
                  ));
            }),
      ],
    );
  }
}
