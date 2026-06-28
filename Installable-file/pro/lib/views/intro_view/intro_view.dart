import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/views/intro_view/components/intro_images.dart';
import 'package:provider/provider.dart';

import '../../services/intro_service.dart';
import 'components/intro_base.dart';

class IntroView extends StatelessWidget {
  static const routeName = "intro_view";
  const IntroView({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<IntroService>(builder: (context, iProvider, child) {
      return Material(
        color: Colors.transparent,
        child: SafeArea(
          top: false,
          bottom: false,
          child: Stack(
            children: [
              Container(
                height: double.infinity,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: context.color.accentContrastColor,
                ),
                child: const Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Expanded(child: IntroImages()),
                    IntroBase(),
                  ],
                ),
              ),
            ],
          ),
        ),
      );
    });
  }
}
