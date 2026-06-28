import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/send_offer_to_job_view/components/send_offer_to_job_button.dart';

import 'components/send_offer_to_job_fields.dart';

class SendOfferToJobView extends StatelessWidget {
  const SendOfferToJobView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.sendAnOffer),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Divider(
              height: 8,
              thickness: 8,
              color: context.color.backgroundColor,
            ),
            const SendOfferToJobFields(),
          ],
        ),
      ),
      bottomNavigationBar: const SendOfferToJobButton(),
    );
  }
}
