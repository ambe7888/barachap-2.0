import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/service_services/add_edit_service_service.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'package:prohand/views/add_edit_service_view/components/add_edit_service_button.dart';
import 'package:provider/provider.dart';

class AddEditServiceView extends StatelessWidget {
  const AddEditServiceView({super.key});

  @override
  Widget build(BuildContext context) {
    final aem = AddEditServiceViewModel.instance;
    return ChangeNotifierProvider(
      create: (context) => AddEditServiceService(),
      child: Scaffold(
        backgroundColor: context.color.accentContrastColor,
        appBar: AppBar(
          leading: const NavigationPopIcon(),
          title: Text(LocalKeys.back),
        ),
        body: PageView(
          controller: aem.pageController,
          physics: const NeverScrollableScrollPhysics(),
          children: aem.steps,
        ),
        bottomNavigationBar: const AddEditServiceButton(),
      ),
    );
  }
}
