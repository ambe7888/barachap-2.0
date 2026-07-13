import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/view_models/landding_view_model/landding_view_model.dart';
import 'package:prohand/views/landing_view/components/landing_bottom_nav.dart';
import 'package:prohand/views/my_services_view/my_services_view.dart';
import 'package:provider/provider.dart';

import '../../services/profile_services/profile_info_service.dart';

import '../home_view/home_view.dart';
import '../job_list_view/job_list_view.dart';
import '../message_list_view/chat_list_view.dart';
import '../order_list_view/order_list_view.dart';

class LandingView extends StatelessWidget {
  const LandingView({super.key});

  @override
  Widget build(BuildContext context) {


    final lvm = LandingViewModel.instance;
    final widgets = [
      const HomeView(),
      const OrderListView(),
      const MyServicesView(),
      const JobListView(),
      const ChatListView(),
    ];
    return Directionality(
      textDirection: context.dProvider.textDirectionRight
          ? TextDirection.rtl
          : TextDirection.ltr,
      child: PopScope(
        canPop: false,
        onPopInvoked: (didPop) {
          lvm.willPopFunction(context);
        },
        child: Scaffold(
          body: ValueListenableBuilder(
            valueListenable: lvm.currentIndex,
            builder: (context, value, child) => widgets[value],
          ),
          bottomNavigationBar: const LandingNavBar(),
        ),
      ),
    );
  }
}
