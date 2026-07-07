import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';

import '../../customizations/colors.dart';
import 'custom_preloader.dart';

class CustomButton extends StatelessWidget {
  final void Function()? onPressed;
  final String btText;
  final bool isLoading;
  final double? height;
  final double? width;
  final Color? backgroundColor;
  final Color? foregroundColor;
  final Color? borderColor;
  final Widget? icon;

  const CustomButton({
    super.key,
    required this.onPressed,
    required this.btText,
    this.isLoading = false,
    this.height = 40,
    this.width,
    this.backgroundColor,
    this.foregroundColor,
    this.borderColor,
    this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: height,
      width: width ?? double.infinity,
      child: ElevatedButton(
        onPressed:
            onPressed == null
                ? null
                : isLoading
                ? () {}
                : () {
                  onPressed!();
                },
        style: ButtonStyle(
          backgroundColor: WidgetStateProperty.resolveWith((states) {
            if (states.contains(WidgetState.disabled)) {
              return (backgroundColor ?? primaryColor).withOpacity(.7);
            }
            if (states.contains(WidgetState.pressed) || isLoading) {
              return context.color.primaryContrastColor;
            }
            return backgroundColor ?? primaryColor;
          }),
          foregroundColor: WidgetStateProperty.resolveWith((states) {
            if (states.contains(WidgetState.disabled)) {
              return context.color.secondaryContrastColor;
            }
            if (states.contains(WidgetState.pressed)) {
              return foregroundColor ?? context.color.accentContrastColor;
            }
            return foregroundColor ?? context.color.accentContrastColor;
          }),
          shape: WidgetStateProperty.resolveWith<OutlinedBorder?>((states) {
            return SmoothRectangleBorder(
              borderRadius: SmoothBorderRadius(
                cornerRadius: 10,
                cornerSmoothing: 0.5,
              ),
            );
          }),
        ),
        child:
            isLoading
                ? SizedBox(
                  height: height! - 8,
                  child: CustomPreloader(
                    whiteColor: context.isDark ? false : true,
                  ),
                )
                : FittedBox(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      if (icon != null) ...[icon!, const SizedBox(width: 8)],
                      Text(
                        btText,
                        maxLines: 1,
                        style: context.titleSmall?.bold6.copyWith(
                          color: context.color.accentContrastColor,
                        ),
                      ),
                    ],
                  ),
                ),
      ),
    );
  }
}
