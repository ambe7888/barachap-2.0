import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/empty_element.dart';
import 'package:provider/provider.dart';

import '../../../services/staff_services/staff_list_service.dart';
import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'selectable_staff_avatar.dart';
import 'staff_avatar_list_skeleton.dart';

class ServiceStaffSelect extends StatelessWidget {
  const ServiceStaffSelect({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    final slProvider = Provider.of<StaffListService>(context, listen: false);

    return CustomFutureWidget(
        function:
            slProvider.shouldAutoFetch ? slProvider.fetchStaffList() : null,
        shimmer: const StaffAvatarListSkeleton(),
        child: Consumer<StaffListService>(builder: (context, sl, child) {
          return ValueListenableBuilder(
              valueListenable: aem.staffs,
              builder: (context, value, child) {
                return (sl.staffListModel.allStaffs ?? []).isEmpty
                    ? Center(child: EmptyElement(text: LocalKeys.noStaffFound))
                    : SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: Wrap(
                          spacing: 12,
                          children: (sl.staffListModel.allStaffs ?? [])
                              .map((staff) => SelectableStaffAvatar(
                                  id: staff.id,
                                  name: staff.fullname ?? "---",
                                  imageUrl: staff.image,
                                  onSelect: () {
                                    aem.selectOrRemoveStaff(
                                        staff.id.toString());
                                  },
                                  isSelected:
                                      value.contains(staff.id.toString())))
                              .toList(),
                        ),
                      );
              });
        }));
  }
}
