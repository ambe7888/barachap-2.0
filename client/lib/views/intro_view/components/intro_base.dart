import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/views/intro_view/components/intro_texts.dart';
import 'package:provider/provider.dart';

import '/services/intro_service.dart';
import '../../../helper/extension/int_extension.dart';
import '../../../helper/local_keys.g.dart';
import '../../../utils/components/custom_button.dart';
import '../../../view_models/intro_view_model/intro_view_model.dart';
import '../../../view_models/landding_view_model/landding_view_model.dart';
import 'dot_indicator.dart';

class IntroBase extends StatelessWidget {
  const IntroBase({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<IntroService>(
      builder: (context, iProvider, child) {
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
                      dotCount: iProvider.introData.length,
                    ),
                  ),
                ],
              ),
              30.toHeight,
              Row(
                children: [
                  Expanded(
                    flex: 1,
                    child: OutlinedButton(
                      onPressed: () {
                        Provider.of<IntroService>(
                          context,
                          listen: false,
                        ).seeIntroValue();
                        LandingViewModel.instance.navigateToLanding(context);
                        return;
                      },
                      child: Text(
                        LocalKeys.skip,
                        style: const TextStyle(color: primaryColor),
                      ),
                    ),
                  ),
                  20.toWidth,
                  Expanded(
                    flex: 1,
                    child: CustomButton(
                      isLoading: false,
                      onPressed: () {
                        if (iProvider.currentIndex ==
                            (iProvider.introData.length - 1)) {
                          Provider.of<IntroService>(
                            context,
                            listen: false,
                          ).seeIntroValue();
                          LandingViewModel.instance.navigateToLanding(context);
                          return;
                        }
                        im.textController.nextPage(
                          duration: 400.milliseconds,
                          curve: Curves.easeIn,
                        );
                        im.imageController.nextPage(
                          duration: 400.milliseconds,
                          curve: Curves.easeIn,
                        );
                      },
                      btText: LocalKeys.continueO,
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
