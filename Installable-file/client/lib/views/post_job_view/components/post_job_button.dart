import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';

import '../../../view_models/post_job_view_model/post_job_view_model.dart';

class PostJobButton extends StatelessWidget {
  const PostJobButton({super.key});

  @override
  Widget build(BuildContext context) {
    final pjm = PostJobViewModel.instance;
    return ValueListenableBuilder(
      valueListenable: pjm.pageIndex,
      builder: (context, index, child) {
        return Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
              color: context.color.accentContrastColor,
              border: Border(
                  top: BorderSide(color: context.color.primaryBorderColor))),
          child: Row(
            children: [
              if (index > 0) ...[
                Expanded(
                  flex: 1,
                  child: OutlinedButton(
                      onPressed: () {
                        pjm.goBack(context);
                      },
                      child: Text(LocalKeys.back)),
                ),
                12.toWidth,
              ],
              Expanded(
                flex: 1,
                child: ElevatedButton(
                    onPressed: () {
                      pjm.continueForward(context);
                    },
                    child: Text(LocalKeys.continueO)),
              ),
            ],
          ),
        );
      },
    );
  }
}
