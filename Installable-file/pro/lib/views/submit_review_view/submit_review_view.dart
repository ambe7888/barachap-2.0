import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';

import './../../helper/local_keys.g.dart';
import './../../utils/components/navigation_pop_icon.dart';
import 'components/submit_review_button.dart';
import 'components/submit_review_client.dart';
import 'components/submit_review_comment.dart';
import 'components/submit_review_stars.dart';

class SubmitReviewView extends StatelessWidget {
  const SubmitReviewView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.submitReview),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(vertical: 8),
        child: Column(
          children: [
            const SubmitReviewClient(),
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
