import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/models/home_models/services_list_model.dart';
import 'package:prohandy_client/services/service/favorite_services_service.dart';
import 'package:provider/provider.dart';

import '../../../customizations/colors.dart';
import '../../components/custom_network_image.dart';

class ServiceCardImage extends StatelessWidget {
  final String? imageUrl;
  final ServiceModel service;
  const ServiceCardImage({super.key, this.imageUrl, required this.service});

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        CustomNetworkImage(
          width: 188,
          height: 128,
          imageUrl: imageUrl.toString(),
          fit: BoxFit.cover,
          radius: 8,
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.end,
          children: [
            Consumer<FavoriteServicesService>(builder: (context, fj, child) {
              final isFav = fj.isFavorite(service.id.toString());
              return GestureDetector(
                onTap: () {
                  if (isFav) {
                    fj.deleteFromFavorite(service.id.toString());
                    return;
                  }
                  fj.addToFavorite(service.id.toString(), service.toJson());
                },
                child: Container(
                  alignment: Alignment.center,
                  padding: 6.paddingAll,
                  margin: 8.paddingAll,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: context.color.accentContrastColor,
                  ),
                  child: Icon(
                    isFav
                        ? Icons.favorite_rounded
                        : Icons.favorite_outline_rounded,
                    size: 20,
                    color: isFav
                        ? primaryColor
                        : context.color.tertiaryContrastColo,
                  ),
                ),
              );
            }),
          ],
        )
      ],
    );
  }
}
