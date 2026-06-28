import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/views/intro_view/components/intro_texts.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '/services/intro_service.dart';
import '../../../helper/local_keys.g.dart';
import '../../../view_models/intro_view_model/intro_view_model.dart';
import '../../../view_models/sign_in_view_model/sign_in_view_model.dart';
import '../../sign_in_view/sign_in_view.dart';
import './../../../helper/extension/int_extension.dart';
import './../../../utils/components/custom_button.dart';
import 'dot_indicator.dart';

class IntroBase extends StatelessWidget {
  const IntroBase({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer<IntroService>(builder: (context, iProvider, child) {
      final im = IntroViewModel.instance;
      return Container(
        color: mutedPrimaryColor,
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 40),
        child: Column(
          children: [
            const IntroTexts(),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                ...iProvider.introData.map(
                  (e) => DotIndicator(
                      iProvider.introData.indexOf(e) == iProvider.currentIndex,
                      dotCount: iProvider.introData.length),
                )
              ],
            ),
            30.toHeight,
            Row(
              children: [
                Expanded(
                  flex: 1,
                  child: OutlinedButton(
                      onPressed: () {
                        Provider.of<IntroService>(context, listen: false)
                            .seeIntroValue();
                        SignInViewModel.instance.initSavedInfo();
                        context.toUntilPage(const SignInView());
                        return;
                      },
                      style: ButtonStyle(
                          shape: WidgetStateProperty.resolveWith((state) {
                        return SmoothRectangleBorder(
                            borderRadius: SmoothBorderRadius(
                              cornerRadius: 10,
                              cornerSmoothing: 0.5,
                            ),
                            side: const BorderSide(color: primaryColor));
                      }), side: WidgetStateBorderSide.resolveWith((states) {
                        return const BorderSide(color: primaryColor);
                      })),
                      child: Text(
                        LocalKeys.skip,
                        style: const TextStyle(
                          color: primaryColor,
                        ),
                      )),
                ),
                20.toWidth,
                Expanded(
                  flex: 1,
                  child: CustomButton(
                    isLoading: false,
                    onPressed: () {
                      if (iProvider.currentIndex ==
                          (iProvider.introData.length - 1)) {
                        Provider.of<IntroService>(context, listen: false)
                            .seeIntroValue();
                        SignInViewModel.instance.initSavedInfo();
                        context.toUntilPage(const SignInView());
                        return;
                      }
                      im.textController.nextPage(
                          duration: 400.milliseconds, curve: Curves.easeIn);
                      im.imageController.nextPage(
                          duration: 400.milliseconds, curve: Curves.easeIn);
                    },
                    btText: LocalKeys.continueO,
                  ),
                ),
              ],
            )
          ],
        ),
      );
    });
  }
}
