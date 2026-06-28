import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/cart_view/components/cart_price_infos.dart';
import 'package:prohand/views/cart_view/components/cart_tile.dart';

class CartView extends StatelessWidget {
  const CartView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(LocalKeys.cart),
      ),
      body: ListView.separated(
        padding: 8.paddingV,
        itemBuilder: (context, index) {
          return const CartTile();
        },
        separatorBuilder: (context, index) {
          return 8.toHeight;
        },
        itemCount: 2,
      ),
      bottomNavigationBar: const CartPriceInfos(),
    );
  }
}
