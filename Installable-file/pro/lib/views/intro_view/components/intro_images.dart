import 'package:flutter/material.dart';
import 'package:prohand/view_models/intro_view_model/intro_view_model.dart';
import 'package:provider/provider.dart';

import '/helper/extension/context_extension.dart';
import '../../../services/intro_service.dart';

class IntroImages extends StatelessWidget {
  const IntroImages({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final iProvider = Provider.of<IntroService>(context, listen: false);
    final im = IntroViewModel.instance;
    return SizedBox(
      child: PageView(
          controller: im.imageController,
          onPageChanged: (index) {
            if (iProvider.currentIndex != index) {
              im.textController.animateToPage(index,
                  duration: const Duration(milliseconds: 400),
                  curve: Curves.easeIn);
            }
            iProvider.setIndex(index);
          },
          children: iProvider.introData
              .map(
                (e) => Container(
                  alignment: Alignment.bottomCenter,
                  margin: context.paddingLowHorizontal,
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      SizedBox(
                        height: context.height / 2,
                        width: context.height / 2,
                        child: Image.asset(e['image'].toString()),
                      ),
                    ],
                  ),
                ),
              )
              .toList()),
    );
  }
}
