import 'package:flutter/material.dart';
import 'package:prohandy_client/view_models/landding_view_model/landding_view_model.dart';
import 'package:prohandy_client/views/landing_view/components/landing_bottom_nav.dart';

import '../home_view/home_view.dart';
import '../job_list_view/job_list_view.dart';
import '../menu_view/menu_view.dart';
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
      const ChatListView(),
      const JobListView(),
      const MenuView(),
    ];
    return PopScope(
      canPop: false,
      onPopInvoked: (didPop) async {
        return Future.value(lvm.willPopFunction(context));
      },
      child: Scaffold(
        body: ValueListenableBuilder(
          valueListenable: lvm.currentIndex,
          builder: (context, value, child) => widgets[value],
        ),
        bottomNavigationBar: const LandingNavBar(),
      ),
    );
  }
}
