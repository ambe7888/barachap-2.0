import 'package:flutter/material.dart';
import 'package:prohand/services/staff_services/staff_list_service.dart';
import 'package:prohand/views/my_staffs_list_view/components/staff_tile.dart';
import 'package:provider/provider.dart';

class StaffsList extends StatelessWidget {
  const StaffsList({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<StaffListService>(builder: (context, sl, child) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: sl.staffListModel.allStaffs?.map((staff) {
              debugPrint(staff.fullname.toString());
              return StaffTile(
                staff: staff,
                isLast:
                    sl.staffListModel.allStaffs?.lastOrNull?.id.toString() ==
                        staff.id.toString(),
              );
            }).toList() ??
            [],
      );
    });
  }
}
