import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/models/address_models/address_model.dart';
import 'package:prohand/utils/components/navigation_pop_icon.dart';
import 'package:prohand/views/add_edit_address_view/add_edit_address_view.dart';

import 'components/address_tile.dart';

class AddressListView extends StatelessWidget {
  const AddressListView({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          LocalKeys.addresses,
        ),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox().divider,
          Expanded(
              child: Scrollbar(
            child: ListView.separated(
              itemBuilder: (context, index) {
                final address = Address(
                  title: "Home",
                  address: "23rd north avn, 16 Road, Beetle, California",
                  phone: "+1038945245",
                );
                return AddressTile(address: address);
              },
              separatorBuilder: (context, index) => Divider(
                color: context.color.primaryBorderColor,
                height: 2,
              ).hp20,
              itemCount: 20,
            ),
          ))
        ],
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ElevatedButton.icon(
          onPressed: () {
            context.toPage(const AddEditAddressView());
          },
          label: Text(LocalKeys.newAddress),
          icon: const Icon(
            Icons.add_circle_outline_rounded,
          ),
        ),
      ),
    );
  }
}
