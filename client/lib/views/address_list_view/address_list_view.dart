import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/constant_helper.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/widget_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/services/address_services/address_list_service.dart';
import 'package:prohandy_client/utils/components/custom_preloader.dart';
import 'package:prohandy_client/utils/components/custom_refresh_indicator.dart';
import 'package:prohandy_client/utils/components/empty_widget.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/add_edit_address_view_model/add_edit_address_view_model.dart';
import 'package:prohandy_client/views/add_edit_address_view/add_edit_address_view.dart';
import 'package:provider/provider.dart';

import 'components/address_tile.dart';

class AddressListView extends StatefulWidget {
  const AddressListView({super.key});

  @override
  State<AddressListView> createState() => _AddressListViewState();
}

class _AddressListViewState extends State<AddressListView> {
  @override
  Widget build(BuildContext context) {
    final alProvider = Provider.of<AddressListService>(context, listen: false);
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
        title: Text(
          LocalKeys.addresses,
        ),
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await alProvider.fetchAddressList().then((_) {
            setState(() {});
          });
        },
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox().divider,
            Consumer<AddressListService>(builder: (context, al, child) {
              return Expanded(
                  child: FutureBuilder(
                      future: al.shouldAutoFetch ? al.fetchAddressList() : null,
                      builder: (_, snap) {
                        if (snap.connectionState == ConnectionState.waiting) {
                          return const Column(
                            children: [
                              CustomPreloader(),
                            ],
                          );
                        }
                        return (al.addressListModel.allLocations.isEmpty ??
                                true)
                            ? EmptyWidget(title: LocalKeys.address)
                            : Scrollbar(
                                child: ListView.separated(
                                  itemBuilder: (context, index) {
                                    final address =
                                        (al.addressListModel.allLocations ??
                                            [])[index];
                                    return AddressTile(address: address);
                                  },
                                  separatorBuilder: (context, index) => Divider(
                                    color: context.color.primaryBorderColor,
                                    height: 2,
                                  ).hp20,
                                  itemCount:
                                      (al.addressListModel.allLocations ?? [])
                                          .length,
                                ),
                              );
                      }));
            })
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        decoration: BoxDecoration(
            color: context.color.accentContrastColor,
            border: Border(
                top: BorderSide(color: context.color.primaryBorderColor))),
        child: ElevatedButton.icon(
          onPressed: () {
            AddEditAddressViewModel.dispose;
            context.toPage(const AddEditAddressView());
          },
          label: Text(LocalKeys.newAddress),
          icon: Icon(
            Icons.add_circle_outline_rounded,
            color: color.accentContrastColor,
          ),
        ),
      ),
    );
  }
}
