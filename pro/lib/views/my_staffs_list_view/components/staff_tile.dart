import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/staff_models/staff_list_model.dart';

import '../../../helper/svg_assets.dart';
import '../../../utils/components/custom_network_image.dart';
import '../../../view_models/staff_view_model/staff_view_model.dart';
import '../../add_new_staff_view/add_new_staff_view.dart';

class StaffTile extends StatelessWidget {
  final Staff staff;
  final bool isLast;
  const StaffTile({
    super.key,
    this.isLast = false,
    required this.staff,
  });

  @override
  Widget build(BuildContext context) {
    final sm = StaffViewModel.instance;
    return Column(
      children: [
        Padding(
          padding: 12.paddingV,
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CustomNetworkImage(
                height: 40,
                width: 40,
                radius: 20,
                fit: BoxFit.cover,
                name: staff.fullname,
                imageUrl: staff.image,
              ),
              12.toWidth,
              Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      staff.fullname ?? "---",
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: context.titleMedium?.bold,
                    ),
                    4.toHeight,
                    Text(
                      staff.email ?? "---",
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: context.bodySmall?.bold,
                    ),
                  ],
                ),
              ),
              12.toWidth,
              PopupMenuButton(
                shape: SmoothRectangleBorder(
                  borderRadius: SmoothBorderRadius(
                    cornerRadius: 10,
                    cornerSmoothing: 1,
                  ),
                ),
                onSelected: (value) {
                  switch (value) {
                    case "edit":
                      StaffViewModel.dispose;
                      StaffViewModel.instance.initStaff(staff);
                      context.toPage(const AddNewStaffView(editing: true));
                      break;
                    default:
                      sm.tryRemovingStaff(context, staff.id);
                  }
                },
                itemBuilder: (context) {
                  return [
                    PopupMenuItem(
                      value: "edit",
                      child: Row(
                        children: [
                          SvgAssets.pencil.toSVGSized(20,
                              color: context.color.tertiaryContrastColo),
                          6.toWidth,
                          Text(
                            LocalKeys.editStaff,
                            style: context.titleSmall,
                          ),
                        ],
                      ),
                    ),
                    PopupMenuItem(
                      value: "remove",
                      child: Row(
                        children: [
                          SvgAssets.trash.toSVGSized(20,
                              color: context.color.primaryWarningColor),
                          6.toWidth,
                          Text(
                            LocalKeys.removeStaff,
                            style: context.titleSmall?.copyWith(
                              color: context.color.primaryWarningColor,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ];
                },
              )
            ],
          ),
        ),
        if (!isLast) 0.toWidth.divider,
      ],
    );
  }
}
