import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:readmore/readmore.dart';

import '../../../customizations/colors.dart';
import '../../../helper/local_keys.g.dart';
import '../../../helper/svg_assets.dart';
import '../../../utils/components/field_with_label.dart';
import '../../../view_models/service_booking_view_model/service_booking_view_model.dart';

class BookingSummaryOrderNote extends StatelessWidget {
  const BookingSummaryOrderNote({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    final ValueNotifier<bool> editing = ValueNotifier(false);
    final svm = ServiceBookingViewModel.instance;
    return ValueListenableBuilder(
        valueListenable: editing,
        builder: (context, edit, child) {
          return Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
            color: context.color.accentContrastColor,
            child: Row(
              children: [
                Expanded(
                  flex: 1,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        LocalKeys.note,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: context.bodySmall?.copyWith(
                            color: context.color.tertiaryContrastColo),
                      ),
                      6.toHeight,
                      if (edit)
                        FieldWithLabel(
                          label: null,
                          hintText: LocalKeys.bookingNoteExmp,
                          controller: svm.descriptionController,
                          minLines: 5,
                          textInputAction: TextInputAction.newline,
                        ),
                      if (!edit)
                        ReadMoreText(
                          svm.descriptionController.text,
                          trimMode: TrimMode.Line,
                          trimLines: 3,
                          colorClickableText: primaryColor,
                          trimCollapsedText: LocalKeys.showMore,
                          trimExpandedText: " ${LocalKeys.showLess}",
                          style: context.bodyMedium,
                        ),
                    ],
                  ),
                ),
                12.toWidth,
                GestureDetector(
                  onTap: () {
                    editing.value = !edit;
                  },
                  child: Container(
                    padding: 10.paddingAll,
                    decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        border: Border.all(color: primaryColor)),
                    child: (edit ? SvgAssets.check : SvgAssets.pencil)
                        .toSVGSized(24, color: primaryColor),
                  ),
                ),
              ],
            ),
          );
        });
  }
}
