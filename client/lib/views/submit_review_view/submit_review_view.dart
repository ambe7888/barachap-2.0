import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/order_models/order_response_model.dart';
import 'package:prohandy_client/view_models/submit_review_view_model/submit_review_view_model.dart';

import './../../helper/local_keys.g.dart';
import './../../utils/components/navigation_pop_icon.dart';
import 'components/submit_review_button.dart';
import 'components/submit_review_comment.dart';
import 'components/submit_review_provider.dart';
import 'components/submit_review_stars.dart';

class SubmitReviewView extends StatelessWidget {
  final SubOrder suborder;
  const SubmitReviewView({super.key, required this.suborder});

  @override
  Widget build(BuildContext context) {
    final srm = SubmitReviewViewModel.instance;
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.submitReview),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(vertical: 8),
        child: Column(
          children: [
            const SubmitReviewProvider(),
            8.toHeight,
            const SubmitReviewStars(),
            8.toHeight,
            const SubmitReviewComment(),
          ],
        ),
      ),
      bottomNavigationBar: const SubmitReviewButton(),
    );
  }
}
