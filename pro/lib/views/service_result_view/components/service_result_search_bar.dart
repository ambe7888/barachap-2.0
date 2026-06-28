import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/svg_assets.dart';

class ResultViwSearchBar extends StatelessWidget {
  const ResultViwSearchBar({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        SizedBox(
            width: context.width - 92,
            height: 40,
            child: TextFormField(
              decoration: InputDecoration(
                prefixIcon: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    FittedBox(
                      child: Row(
                        children: [
                          4.toWidth,
                          SvgAssets.search.toSVGSized(24,
                              color: context.color.primaryContrastColor),
                        ],
                      ),
                    )
                  ],
                ),
              ),
            )),
        12.toWidth,
        IconButton(
          onPressed: () {},
          iconSize: 20,
          padding: const EdgeInsets.all(10),
          icon: SvgAssets.filter
              .toSVGSized(20, color: context.color.primaryContrastColor),
          style: ButtonStyle(
            shape: WidgetStateProperty.resolveWith<OutlinedBorder?>((states) {
              return SmoothRectangleBorder(
                  borderRadius: SmoothBorderRadius(
                    cornerRadius: 10,
                    cornerSmoothing: 0.5,
                  ),
                  side: BorderSide(color: context.color.primaryBorderColor));
            }),
          ),
        ),
      ],
    );
  }
}
