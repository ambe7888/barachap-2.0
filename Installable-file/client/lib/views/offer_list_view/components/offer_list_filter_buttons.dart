import 'package:flutter/material.dart';
import 'package:prohandy_client/view_models/offer_list_view_model/offer_list_view_model.dart';

class ApplicantsFilterButtons extends StatelessWidget {
  const ApplicantsFilterButtons({super.key});

  @override
  Widget build(BuildContext context) {
    final avm = OfferListViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: avm.selectedType,
      builder: (context, value, child) {
        return const SingleChildScrollView(
          scrollDirection: Axis.horizontal,
        );
      },
    );
  }
}
