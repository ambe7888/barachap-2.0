import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/utils/components/image_view.dart';

import '../../../utils/components/image_pl_widget.dart';

class ExpansionChildren extends StatelessWidget {
  final note;
  final String? image;
  final String? path;
  const ExpansionChildren(
    this.note,
    this.image,
    this.path, {
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    debugPrint("$path/$image".toString());
    return Column(
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              flex: 4,
              child: GestureDetector(
                onTap: () {
                  context.toPage(ImageView("$image"));
                },
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: CachedNetworkImage(
                    fit: BoxFit.cover,
                    imageUrl: "$image".toString(),
                    placeholder: (context, url) {
                      return const ImagePLWidget(size: 60);
                    },
                    errorWidget: (context, url, error) {
                      return const ImagePLWidget(size: 60);
                    },
                  ),
                ),
              ),
            ),
            const Expanded(
              flex: 1,
              child: SizedBox(),
            ),
            Expanded(
              flex: 12,
              child: Container(
                child: Text(note ?? "---"),
              ),
            ),
          ],
        )
      ],
    );
  }
}
