import 'package:flutter/material.dart';

import '/helper/extension/string_extension.dart';
import '../../app_static_values.dart';
import '../../helper/extension/context_extension.dart';
import '../../helper/extension/int_extension.dart';
import '../../utils/components/custom_preloader.dart';
import 'empty_spacer_helper.dart';
import 'field_label.dart';

class FieldWithLabel extends StatelessWidget {
  final String? label;
  final String hintText;
  final initialValue;
  final onChanged;
  final onFieldSubmitted;
  final String? Function(String? value)? validator;
  final keyboardType;
  final textInputAction;
  final String? svgPrefix;
  final AutovalidateMode? autovalidateMode;
  final ValueNotifier<Status>? errorNotifier;
  final errorText;
  final successText;
  final Iterable<String>? autofillHints;
  final controller;
  final isRequired;
  final int? minLines;
  final int? maxLines;
  final Widget? prefixIcon;
  final void Function(PointerDownEvent)? onTapOutside;
  final bool? readOnly;
  final bool isSuccess;
  const FieldWithLabel(
      {super.key,
      required this.label,
      required this.hintText,
      this.initialValue,
      this.onChanged,
      this.onFieldSubmitted,
      this.validator,
      this.keyboardType,
      this.textInputAction,
      this.svgPrefix,
      this.autovalidateMode,
      this.isRequired,
      this.errorNotifier,
      this.errorText,
      this.successText,
      this.prefixIcon,
      this.autofillHints,
      this.maxLines,
      this.minLines,
      this.controller,
      this.onTapOutside,
      this.isSuccess = false,
      this.readOnly});

  setInitialValue(value) {
    if (value == null || value.isEmpty) {
      return;
    }

    controller?.text = value;
  }

  @override
  Widget build(BuildContext context) {
    setInitialValue(initialValue);
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (label != null)
          FieldLabel(label: label!, isRequired: isRequired ?? false),
        TextFormField(
          readOnly: (readOnly ?? false) || isSuccess,
          style: isSuccess ? context.titleSmall?.copyWith(color: Colors.green, fontWeight: FontWeight.bold) : context.titleSmall,
          keyboardType: keyboardType,
          textInputAction: textInputAction ??
              (minLines != null
                  ? TextInputAction.newline
                  : TextInputAction.next),
          controller: controller,
          autovalidateMode: autovalidateMode,
          autofillHints: autofillHints,
          minLines: minLines,
          maxLines: maxLines,
          decoration: InputDecoration(
              hintText: hintText,
              enabledBorder: isSuccess ? OutlineInputBorder(borderSide: const BorderSide(color: Colors.green, width: 2), borderRadius: BorderRadius.circular(8)) : null,
              focusedBorder: isSuccess ? OutlineInputBorder(borderSide: const BorderSide(color: Colors.green, width: 2), borderRadius: BorderRadius.circular(8)) : null,
              prefixIcon: svgPrefix != null || prefixIcon != null
                  ? Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      child: prefixIcon ?? svgPrefix!.toSVG,
                    )
                  : null,
              suffixIcon: errorNotifier == null
                  ? null
                  : ValueListenableBuilder(
                      valueListenable: errorNotifier!,
                      builder: (context, value, child) {
                        return Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            SizedBox(
                              height: 20,
                              width: 20,
                              child: value == Status.NOT_INITIATED ||
                                      value == Status.INVALID
                                  ? const SizedBox()
                                  : value == Status.NOT_AVAILABLE
                                      ? Icon(
                                          Icons.close_rounded,
                                          color:
                                              context.color.primaryWarningColor,
                                        )
                                      : value == Status.AVAILABLE
                                          ? Icon(
                                              Icons.done_rounded,
                                              color: context
                                                  .color.primarySuccessColor,
                                            )
                                          : const CustomPreloader(),
                            ),
                          ],
                        );
                      })),
          onChanged: onChanged,
          validator: validator,
          onFieldSubmitted: onFieldSubmitted,
          onTapOutside: (event) {
            if (onTapOutside != null) {
              onTapOutside!(event);
            } else if (minLines != null) {
              context.unFocus;
            }
          },
        ),
        if (errorNotifier != null) ...[
          8.toHeight,
          ValueListenableBuilder(
              valueListenable: errorNotifier!,
              builder: (context, error, child) {
                return error == Status.NOT_INITIATED ||
                        error == Status.LOADING ||
                        error == Status.INVALID
                    ? const SizedBox()
                    : Text(
                        error == Status.NOT_AVAILABLE ? errorText : successText,
                        style: context.titleMedium?.copyWith(
                            color: context.color.accentContrastColor),
                      );
              })
        ],
        EmptySpaceHelper.emptyHeight(16),
      ],
    );
  }
}
