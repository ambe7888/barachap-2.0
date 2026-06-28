import 'package:figma_squircle_updated/figma_squircle.dart';
import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/staff_services/staff_list_service.dart';
import 'package:prohand/utils/components/custom_future_widget.dart';
import 'package:prohand/utils/components/custom_preloader.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/staff_view_model/staff_view_model.dart';
import 'package:provider/provider.dart';

import '../../utils/components/empty_widget.dart';
import '../add_new_staff_view/add_new_staff_view.dart';
import 'components/staffs_list.dart';

class MyStaffsListView extends StatelessWidget {
  const MyStaffsListView({super.key});

  @override
  Widget build(BuildContext context) {
    final slProvider = Provider.of<StaffListService>(context, listen: false);
    return Scaffold(
      backgroundColor: context.color.cardFillColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.myStaffs),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await slProvider.fetchStaffList();
        },
        child: CustomFutureWidget(
          function:
              slProvider.shouldAutoFetch ? slProvider.fetchStaffList() : null,
          shimmer: const CustomPreloader(),
          child: Consumer<StaffListService>(builder: (context, sl, child) {
            if (sl.staffListModel.allStaffs?.isEmpty ?? true) {
              return EmptyWidget(title: LocalKeys.noStaffFound);
            }
            return SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              child: Column(
                children: [
                  Divider(
                    height: 8,
                    thickness: 8,
                    color: context.color.backgroundColor,
                  ),
                  16.toHeight,
                  const StaffsList().hp20,
                  16.toHeight,
                ],
              ),
            );
          }),
        ),
      ),
      bottomNavigationBar: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        child: ElevatedButton.icon(
          onPressed: () {
            StaffViewModel.dispose;
            context.toPage(const AddNewStaffView());
          },
          label: Text(LocalKeys.newStaff),
          icon: const Icon(
            Icons.add_circle_outline_rounded,
          ),
          style: ButtonStyle(
            backgroundColor: WidgetStateProperty.resolveWith((states) {
              return mutedPrimaryColor;
            }),
            foregroundColor: WidgetStateProperty.resolveWith((states) {
              if (states.contains(WidgetState.disabled)) {
                return context.color.secondaryContrastColor;
              }

              return primaryColor;
            }),
            shape: WidgetStateProperty.resolveWith<OutlinedBorder?>((states) {
              return SmoothRectangleBorder(
                borderRadius: SmoothBorderRadius(
                  cornerRadius: 12,
                  cornerSmoothing: 1,
                ),
              );
            }),
          ),
        ),
      ),
    );
  }
}
