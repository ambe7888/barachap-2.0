import 'package:flutter/material.dart';
import 'package:prohandy_client/customizations/colors.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/models/service/service_details_model.dart';
import 'package:prohandy_client/utils/components/alerts.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/views/cart_view/components/cart_price_infos.dart';
import 'package:prohandy_client/views/cart_view/components/cart_tile.dart';
import 'package:provider/provider.dart';

import '../../services/profile_services/profile_info_service.dart';
import '../../services/service/cart_service.dart';
import '../account_skeleton/account_skeleton.dart';

class CartView extends StatelessWidget {
  const CartView({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ProfileInfoService>(builder: (context, pi, child) {
      if (pi.profileInfoModel.userDetails == null) {
        return Scaffold(
            appBar: AppBar(
              leading: const NavigationPopIcon(),
              title: Text(LocalKeys.cart),
            ),
            body: const AccountSkeleton());
      }
      return Consumer<CartService>(builder: (context, cs, child) {
        return Scaffold(
          appBar: AppBar(
            leading: const NavigationPopIcon(),
            title: Text(LocalKeys.cart),
            actions: [
              TextButton.icon(
                  onPressed: () {
                    Alerts().confirmationAlert(
                        context: context,
                        title: LocalKeys.areYouSure,
                        buttonText: LocalKeys.clear,
                        onConfirm: () async {
                          cs.clearCart();
                          context.pop;
                        });
                  },
                  label: Text(LocalKeys.clearCart),
                  icon: SvgAssets.trash.toSVGSized(18, color: primaryColor)),
            ],
          ),
          body: cs.cartList.values.isEmpty
              ? EmptyWidget(
                  title: LocalKeys.noServiceAddedYet,
                )
              : ListView.separated(
                  padding: 8.paddingV,
                  itemBuilder: (context, index) {
                    final cartItem = cs.cartList.values.toList()[index];
                    final service =
                        ServiceDetails.fromJson(cartItem["service"]);
                    return CartTile(
                      totalAmount: cartItem["total"].toString().tryToParse +
                          cartItem["tax"].toString().tryToParse,
                      serviceTitle: service.title,
                      serviceImage: service.image,
                      serviceId: service.id,
                      cartItem: cartItem,
                    );
                  },
                  separatorBuilder: (context, index) {
                    return 8.toHeight;
                  },
                  itemCount: cs.cartList.length,
                ),
          bottomNavigationBar: cs.cartList.values.isEmpty
              ? const SizedBox()
              : CartPriceInfos(cs: cs),
        );
      });
    });
  }
}
