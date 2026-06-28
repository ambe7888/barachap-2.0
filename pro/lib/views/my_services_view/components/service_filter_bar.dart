import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:provider/provider.dart';

import '../../../helper/svg_assets.dart';
import '../../../services/service_services/service_list_service.dart';
import '../../../view_models/my_services_view_model/my_services_view_model.dart';

class ServiceFilterBar extends StatelessWidget {
  const ServiceFilterBar({super.key});

  @override
  Widget build(BuildContext context) {
    final msm = MyServicesViewModel.instance;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: 24.paddingH,
          child: Row(
            children: [
              Expanded(
                  flex: 1,
                  child: TextFormField(
                    controller: msm.titleController,
                    textInputAction: TextInputAction.search,
                    decoration: InputDecoration(
                      hintText: LocalKeys.search,
                      prefixIcon: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          FittedBox(
                            child: Row(
                              children: [
                                4.toWidth,
                                SvgAssets.search.toSVGSized(24,
                                    color: context.color.primaryContrastColor),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                    onFieldSubmitted: (value) {
                      if ((value).trim().isNotEmpty) {
                        Provider.of<ServiceListService>(context, listen: false)
                            .fetchServiceList();
                      }
                    },
                  )),
            ],
          ),
        ),
        12.toHeight,
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          padding: 24.paddingH,
          child: Wrap(
            spacing: 6,
            children: msm.serviceStatusValues.keys.map((status) {
              return ValueListenableBuilder(
                valueListenable: msm.selectedStatus,
                builder: (context, value, child) {
                  return _button(
                      title: status,
                      status: value,
                      isSelected: value == status,
                      onPressed: () {
                        msm.selectedStatus.value = status;
                        Provider.of<ServiceListService>(context, listen: false)
                            .fetchServiceList();
                      });
                },
              );
            }).toList(),
          ),
        )
      ],
    );
  }

  _button({
    required String title,
    required status,
    bool isSelected = false,
    onPressed,
  }) {
    return isSelected
        ? ElevatedButton(
            onPressed: () {},
            child: Text(title),
          )
        : OutlinedButton(
            onPressed: onPressed,
            child: Text(title),
          );
  }
}
