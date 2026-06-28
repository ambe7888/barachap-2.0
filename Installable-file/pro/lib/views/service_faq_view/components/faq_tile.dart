import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:provider/provider.dart';

import '../../../helper/constant_helper.dart';
import '../../../services/service_services/add_edit_service_service.dart';
import '../../../utils/components/custom_squircle_widget.dart';
import '../../../view_models/add_edit_service_view_model/add_edit_service_view_model.dart';
import 'add_faq_sheet.dart';

class FaqTile extends StatelessWidget {
  final String title;
  final String answer;
  final id;
  final void Function()? onEdit;
  final void Function()? onRemove;
  const FaqTile({
    super.key,
    required this.title,
    required this.answer,
    this.id,
    this.onEdit,
    this.onRemove,
  });

  @override
  Widget build(BuildContext context) {
    return SquircleContainer(
        radius: 10,
        borderColor: context.color.primaryBorderColor,
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(
                    flex: 1,
                    child: Text(
                      title,
                      style: context.titleSmall?.bold,
                    )),
                GestureDetector(
                    onTapDown: (details) {
                      Alerts.showPopupMenu(context, details, {
                        "edit": LocalKeys.edit,
                        "remove": LocalKeys.remove,
                      }, (v) {
                        switch (v) {
                          case "edit":
                            final titleCon = TextEditingController();
                            final descCon = TextEditingController();
                            titleCon.text = title;
                            descCon.text = answer;
                            showModalBottomSheet(
                              context: context,
                              isScrollControlled: true,
                              backgroundColor: context.color.cardFillColor,
                              builder: (context) {
                                return AddFaqSheet(
                                  id: id,
                                  title: titleCon,
                                  description: descCon,
                                );
                              },
                            ).then((result) {
                              if (result == false) return;
                              Provider.of<AddEditServiceService>(context,
                                      listen: false)
                                  .refresh();
                            });
                            break;
                          default:
                            AddEditServiceViewModel.instance
                                .addEditRemoveFAQ(id);
                            Provider.of<AddEditServiceService>(context,
                                    listen: false)
                                .refresh();
                        }
                      });
                    },
                    child: Icon(
                      Icons.more_vert_rounded,
                      size: 20,
                      color: context.color.tertiaryContrastColo,
                    )),
              ],
            ),
            12.toHeight,
            Divider(
              color: color.mutedContrastColor,
              thickness: 1,
              height: 1,
            ),
            8.toHeight,
            Text(
              answer,
              style: context.bodySmall,
            )
          ],
        ));
  }
}
